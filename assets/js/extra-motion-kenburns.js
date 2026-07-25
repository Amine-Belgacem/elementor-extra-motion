/**
 * Handles Ken Burns effect animations for elements with a specified ken-burns-data attribute.
 */
class KenBurnsHandler {

    /**
     * Creates a new KenBurnsHandler instance.
     * @param {string} containersSelector - The CSS selector for Ken Burns containers.
     */
    constructor() {
        this.containers = document.querySelectorAll('.ken-burns-container');

        if (!this.containers.length) {
            console.error('No containers found with the specified selector.');
            return;
        }

        this.init();
    }

    /**
     * Initializes the Ken Burns effect animation for each container.
     */
    init() {
        this.containers.forEach((container, index) => {
            this.container = container;

            const kenBurnsData = container.getAttribute('ken-burns-data');
            if (!kenBurnsData) {
                console.error(`Ken Burns data attribute missing for container ${index}.`);
                return;
            }

            try {
                this.attributes = JSON.parse(kenBurnsData);
            } catch (error) {
                console.error(`Error parsing JSON data for container ${index}:`, error);
                return;
            }

            this.overlay = this.createOverlay();
            this.translate = this.calculateDirections();

            this.keyframes = this.generateKeyframes(index);

            const animationStyle = document.createElement('style');
            animationStyle.textContent = this.keyframes;
            document.head.appendChild(animationStyle);

            this.applyStyles(index);
        });
    }

    /**
     * Calculates translation directions based on Ken Burns data.
     * @returns {number[]} The translation directions.
     */
    calculateDirections() {
        const scaleFactor = this.attributes['scale-factor'];
        const scaled = 10 * scaleFactor;
        const halfScaled = 0.5 * scaled;
        
        const directions = {
            'center-center': [0, 0],
            'center-left': [scaled, 0],
            'center-right': [-halfScaled, 0],
            'top-center': [0, scaled],
            'top-left': [scaled, scaled],
            'top-right': [-halfScaled, scaled],
            'bottom-center': [0, -scaled],
            'bottom-left': [scaled, -scaled],
            'bottom-right': [-halfScaled, -scaled],
        };

        return directions[this.attributes.direction];
    }

    /**
     * Creates an overlay for Ken Burns effect.
     * @returns {HTMLElement} The created overlay element.
     */
    createOverlay() {
        const overlay = document.createElement('div');
        overlay.classList.add('ken-burns-overlay');
        this.container.insertBefore(overlay, this.container.firstChild);

        const overlayColor = this.attributes['overlay-color'];

        if (overlayColor && overlayColor !== '#00000000') {
            const colorLayer = document.createElement('div');
            colorLayer.classList.add('ken-burns-overlay-color');
            colorLayer.style.backgroundColor = overlayColor;
            this.container.insertBefore(colorLayer, overlay.nextSibling);
        }

        return overlay;
    }

    /**
     * Generates keyframes for the Ken Burns effect animation.
     * @param {number} index - The index of the container.
     * @returns {string} The generated keyframes CSS.
     */
    generateKeyframes(index) {
        const infinite = this.attributes.infinite;
        const scaleFactor = this.attributes['scale-factor'];
        const translate = this.translate;

        return `
        @keyframes ken-burns-${index} {
            0% {
                transform: scale(1);
            }
            ${infinite ? 
            `50% {
                transform: scale(${scaleFactor}) 
                translate(${translate[0]}%, ${translate[1]}%);
            }` : ''}
            100% {
                transform: scale(${infinite ? '1' : scaleFactor}) 
                translate(
                ${infinite ? '0%, 0%' : `${translate[0]}%, ${translate[1]}%`}
                );
            }
        }
        `;
    }

    /**
     * Applies animation styles to the container.
     * @param {number} index - The index of the container.
     */
    applyStyles(index) {
        const overlay = this.overlay;
        const duration = this.attributes.duration;
        const iteration = this.attributes.infinite ? 'infinite' : 'forwards';

        const style = window.getComputedStyle(this.container);

        overlay.style.backgroundImage = style.getPropertyValue('background-image');
        overlay.style.backgroundPosition = style.getPropertyValue('background-position');
        overlay.style.backgroundSize = style.getPropertyValue('background-size');
        overlay.style.backgroundRepeat = style.getPropertyValue('background-repeat');

        overlay.style.animation = `ken-burns-${index} ${duration}s ease ${iteration}`;
        overlay.style.animationTimingFunction = 'ease-in-out';

        this.container.style.backgroundImage = 'none';
    }
}