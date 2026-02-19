<div x-data="shareButtons('{{ $url }}','{{ $title }}')" class="flex flex-col gap-3">
    <button
        @click="shareFacebook()"
        :disabled="isSharing"
        :class="isSharing ? 'opacity-50 cursor-not-allowed' : ''"
        class="bg-blue-500 text-white py-2 rounded-lg flex items-center justify-center gap-2 hover:bg-blue-600 transition">
        <span x-show="!isSharing">
            <i class="bi bi-facebook"></i> Facebook
        </span>
        <span x-show="isSharing">
            Compartiendo...
        </span>
    </button>
    <button
        @click="shareTwitter()"
        :disabled="isSharing"
        :class="isSharing ? 'opacity-50 cursor-not-allowed' : ''"
        class="bg-gray-800 text-white py-2 rounded-lg flex items-center justify-center gap-2 hover:bg-blue-700 transition">
        <span x-show="!isSharing">
            <i class="bi bi-twitter"></i> Twitter / X
        </span>
        <span x-show="isSharing">
            Compartiendo...
        </span>
    </button>
    <button
        @click="shareWhatsapp()"
        :disabled="isSharing"
        :class="isSharing ? 'opacity-50 cursor-not-allowed' : ''"
        class="bg-green-500 text-white py-2 rounded-lg flex items-center justify-center gap-2 hover:bg-blue-700 transition">
        <span x-show="!isSharing">
            <i class="bi bi-whatsapp"></i> WhatsApp
        </span>
        <span x-show="isSharing">
            Compartiendo...
        </span>
    </button>
    <button
        @click="shareLinkedin()"
        :disabled="isSharing"
        :class="isSharing ? 'opacity-50 cursor-not-allowed' : ''"
        class="bg-blue-700 text-white py-2 rounded-lg flex items-center justify-center gap-2 hover:bg-blue-800 transition">
        <span x-show="!isSharing">
            <i class="bi bi-linkedin"></i> LinkedIn
        </span>
        <span x-show="isSharing">
            Compartiendo...
        </span>
    </button>
</div>

<script>
    function shareButtons(pagina_url, pagina_titulo) {
        return {
            url: pagina_url,
            text: pagina_titulo,
            isSharing: false,

            openPopup(url, width = 600, height = 600) {
                const left = (window.innerWidth / 2) - (width / 2);
                const top = (window.innerHeight / 2) - (height / 2);

                window.open(
                    url,
                    '_blank',
                    `width=${width},height=${height},top=${top},left=${left},
                    toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes`
                );
            },

            async handleShare(callback) {
                if (this.isSharing) return;

                this.isSharing = true;
                callback();

                // Espera 2 segundos antes de permitir otro click
                setTimeout(() => {
                    this.isSharing = false;
                }, 2000);
            },

            shareFacebook() {
                this.handleShare(() => {
                    const shareUrl =
                        `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(this.url)}`;
                    this.openPopup(shareUrl, 600, 500);
                });
            },

            shareTwitter() {
                this.handleShare(() => {
                    const shareUrl =
                        `https://twitter.com/intent/tweet?url=${encodeURIComponent(this.url)}&text=${encodeURIComponent(this.text)}`;
                    this.openPopup(shareUrl, 600, 400);
                });
            },

            shareWhatsapp() {
                this.handleShare(() => {
                    const shareUrl =
                        `https://api.whatsapp.com/send?text=${encodeURIComponent(this.text + ' ' + this.url)}`;
                    this.openPopup(shareUrl, 500, 600);
                });
            },

            shareLinkedin() {
                this.handleShare(() => {
                    const shareUrl =
                        `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(this.url)}`;
                    this.openPopup(shareUrl, 600, 500);
                });
            }
        }
    }
</script>
