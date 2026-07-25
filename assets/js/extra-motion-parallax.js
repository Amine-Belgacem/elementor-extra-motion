/**
 * Handles parallax effect for elements with a specified parallax-data attribute.
 */
class ParallaxHandler {
    
    /**
     * Creates a new ParallaxHandler instance.
     */
    constructor() {
        this.containers = document.querySelectorAll('.parallax-container');

        if (!this.containers.length) {
            console.error('No containers found with the specified selector.');
            return;
        }

        this.init();
    }

    /**
     * Initializes the parallax effect for each container.
     */
    init() {
        this.containers.forEach((container, index) => {
            this.container = container;

            const parallaxData = container.getAttribute('parallax-data');
            if (!parallaxData) {
                console.error(`Parallax data attribute missing for container ${index}.`);
                return;
            }

            try {
                this.attributes = JSON.parse(parallaxData);
            } catch (error) {
                console.error(`Error parsing JSON data for container ${index}:`, error);
                return;
            }

            this.overlay = this.createOverlay();
            this.applyStyles();
            this.image = new Image();
            this.image.src = this.getImageURL();
            this.image.onload = () => {
                this.overlayNewHeight = this.getOverlayNewHeight(
                    this.image.height,
                    this.image.width,
                    this.attributes['scale-factor']
                );

                this.container.style.backgroundImage = 'none';
                this.overlay.style.height = this.overlayNewHeight + 'px';
                this.overlay.style.transition = 'none';
                this.translateOverlay();

                window.addEventListener('scroll', () => {
                    if (
                        this.isContainerInViewport() && 
                        this.isOverlayTallerThanContainer()
                    ) {
                        this.overlay.style.transition = 
                            `transform ${this.attributes['duration']}s ease-out`;
                        this.translateOverlay();
                    }
                });
            };
            this.image.onerror = () => {
                console.error(`Error loading image for container ${index}.`);
            };
        });
    }

    /**
     * Creates an overlay for the parallax effect.
     * @returns {HTMLElement} The created overlay element.
     */
    createOverlay() {
        const overlay = document.createElement('div');
        overlay.classList.add('parallax-overlay');
        this.container.appendChild(overlay);
        return overlay;
    }

    /**
     * Applies styles to the overlay based on container styles.
     */
    applyStyles() {
        const style = window.getComputedStyle(this.container);
        this.overlay.style.backgroundImage = style.getPropertyValue('background-image');
        this.overlay.style.backgroundPosition = style.getPropertyValue('background-position');
        this.overlay.style.backgroundSize = style.getPropertyValue('background-size');
        this.overlay.style.backgroundRepeat = style.getPropertyValue('background-repeat');
    }

    /**
     * Retrieves the URL of the background image of the container.
     * @returns {string} The URL of the background image.
     */
    getImageURL() {
        const style = window.getComputedStyle(this.container);
        const backgroundImage = style.getPropertyValue('background-image');
        return backgroundImage.replace('url("', '').replace('")', '');
    }

    /**
     * Calculates the translate value for the overlay based on scrolling.
     * @returns {number} The calculated translateY value.
     */
    calculateTranslate() {
        const containerTop = this.container.getBoundingClientRect().top;
        const translateOffset = this.overlayNewHeight - this.container.offsetHeight;
        const containerTotalHeight = window.innerHeight + this.container.offsetHeight;
        const distanceFromTop = window.innerHeight - containerTop;
        const visibilityPercentage = Math.max(
            0, Math.min(1, distanceFromTop / containerTotalHeight)
        );
        const factor = this.attributes['translate-factor'] / this.attributes['scale-factor'];
        return (-translateOffset * factor) * visibilityPercentage;
    }

    /**
     * Calculates the new height of the overlay based on image dimensions and scaleFactor.
     * @param {number} imageHeight - The height of the background image.
     * @param {number} imageWidth - The width of the background image.
     * @param {number} scaleFactor - The scale factor for the overlay.
     * @returns {number} The calculated new height of the overlay.
     */
    getOverlayNewHeight(imageHeight, imageWidth, scaleFactor) {
        const containerAspectRatio = this.overlay.offsetWidth / this.overlay.offsetHeight;
        const imageAspectRatio = imageWidth / imageHeight;

        if (containerAspectRatio > imageAspectRatio) {
            return (this.overlay.offsetWidth / imageAspectRatio) * scaleFactor;
        } else {
            return this.overlay.offsetHeight * scaleFactor;
        }
    }

    /**
     * Checks if the container is in the viewport.
     * @returns {boolean} True if the container is in the viewport, false otherwise.
     */
    isContainerInViewport() {
        const containerTop = this.container.getBoundingClientRect().top;
        return (
            containerTop < window.innerHeight && 
            containerTop + this.container.offsetHeight > 0
        );
    }

    /**
     * Checks if the overlay is taller than the container.
     * @returns {boolean} True if the overlay is taller than container, false otherwise.
     */
    isOverlayTallerThanContainer() {
        return this.overlayNewHeight > this.container.offsetHeight;
    }

    /**
     * Translates the overlay based on scrolling.
     */
    translateOverlay() {
        const translateY = this.calculateTranslate();
        this.overlay.style.transform = 'translateY(' + translateY + 'px)';
    }
}