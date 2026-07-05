
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.store('viewer', {
    open: false,
    index: 0,
    images: [],
    touchStartX: 0,
    get current() { return this.images[this.index] || {}; },
    show(index) {
        this.index = index;
        this.open = true;
        document.body.style.overflow = 'hidden';
    },
    close() {
        this.open = false;
        document.body.style.overflow = '';
    },
    prev() { if (this.index > 0) this.index--; },
    next() { if (this.index < this.images.length - 1) this.index++; },
});

Alpine.data('uploadQueue', (endpoint, folderId, maxUploadBytes) => ({
    endpoint,
    folderId,
    maxUploadBytes,
    queue: [],
    uploading: false,

    get overallProgress() {
        if (! this.queue.length) return 0;
        return Math.round(this.queue.reduce((sum, f) => sum + f.progress, 0) / this.queue.length);
    },

    get hasPending() {
        return this.queue.some(f => f.status === 'pending' || f.status === 'error');
    },

    get isDone() {
        return this.queue.length > 0 && this.queue.every(f => f.status === 'done');
    },

    addFiles(fileList) {
        Array.from(fileList).forEach(file => {
            const tooLarge = this.maxUploadBytes && file.size > this.maxUploadBytes;

            this.queue.push({
                file,
                name: file.name,
                size: file.size,
                progress: 0,
                status: tooLarge ? 'error' : 'pending',
                error: tooLarge ? `File exceeds the server's ${this.formatSize(this.maxUploadBytes)} upload limit.` : null,
                xhr: null,
            });
        });
    },

    removeFile(item) {
        if (item.status === 'uploading' && item.xhr) item.xhr.abort();
        this.queue = this.queue.filter(f => f !== item);
    },

    retry(item) {
        item.status = 'pending';
        item.error = null;
        item.progress = 0;
        this.startUpload();
    },

    reset() {
        this.queue = [];
        this.uploading = false;
    },

    async startUpload() {
        if (this.uploading) return;
        this.uploading = true;

        for (const item of this.queue) {
            if (item.status === 'pending') {
                await this.uploadOne(item);
            }
        }

        this.uploading = false;

        if (this.isDone) {
            setTimeout(() => window.location.reload(), 700);
        }
    },

    uploadOne(item) {
        return new Promise((resolve) => {
            item.status = 'uploading';
            item.progress = 0;
            item.error = null;

            const formData = new FormData();
            formData.append('folder_id', this.folderId ?? '');
            formData.append('files[]', item.file);

            const xhr = new XMLHttpRequest();
            item.xhr = xhr;

            xhr.open('POST', this.endpoint, true);
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('Accept', 'application/json');

            xhr.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable) {
                    item.progress = Math.round((e.loaded / e.total) * 100);
                }
            });

            xhr.addEventListener('load', () => {
                if (xhr.status >= 200 && xhr.status < 300) {
                    item.progress = 100;
                    item.status = 'done';
                } else {
                    item.status = 'error';
                    item.error = this.parseError(xhr);
                }
                resolve();
            });

            xhr.addEventListener('error', () => {
                item.status = 'error';
                item.error = 'Network error — check your connection and try again.';
                resolve();
            });

            xhr.addEventListener('abort', () => {
                item.status = 'pending';
                resolve();
            });

            xhr.send(formData);
        });
    },

    parseError(xhr) {
        if (xhr.status === 413) return 'File is too large for the server to accept.';

        try {
            const data = JSON.parse(xhr.responseText);
            if (data.errors) return Object.values(data.errors).flat().join(' ');
            if (data.message) return data.message;
        } catch (e) {
            // response wasn't JSON, fall through to generic message
        }

        return `Upload failed (HTTP ${xhr.status}).`;
    },

    formatSize(bytes) {
        if (! bytes) return '0 B';
        const units = ['B', 'KB', 'MB', 'GB'];
        let value = bytes;
        let power = 0;
        while (value >= 1024 && power < units.length - 1) {
            value /= 1024;
            power++;
        }
        return value.toFixed(power === 0 ? 0 : 1) + ' ' + units[power];
    },
}));

Alpine.start();
