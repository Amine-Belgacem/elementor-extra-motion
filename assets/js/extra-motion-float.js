/**
 * Handles floating animations for elements with a specified floating data attribute.
 */
class FloatingHandler {
    
    /**
     * Constructs a new FloatingHandler instance.
     */
    constructor() {
        this.containers = document.querySelectorAll('.floating-container');

        if (!this.containers.length) {
            console.error('No containers found with the specified selector.');
            return;
        }
        
        this.init();
    }

    /**
     * Initializes the floating animation for each container.
     */
    init() {
        this.containers.forEach((container, index) => {
            this.container = container;
            
            const floatingData = container.getAttribute('floating-data');
            if (!floatingData) {
                console.error(`Floating data attribute missing for container ${index}.`);
                return;
            }

            try {
                this.attributes = JSON.parse(floatingData);
            } catch (error) {
                console.error(`Error parsing JSON data for container ${index}:`, error);
                return;
            }

            if (!this.attributes.distance || !this.attributes.duration) {
                console.error(`Missing required attributes for container ${index}.`);
                return;
            }

            this.keyframes = this.generateKeyframes(index);

            const animationStyle = document.createElement('style');
            animationStyle.textContent = this.keyframes;
            document.head.appendChild(animationStyle);

            this.applyStyles(index);
        });
    }

    /**
     * Generates keyframes for the floating animation.
     * @param {number} index - The index of the container.
     * @returns {string} The generated keyframes CSS.
     */
    generateKeyframes(index) {
        const distance = this.attributes.distance;
        return `
            @keyframes floating-${index} {
                0% {
                    transform: translateY(0px);
                }
                50% {
                    transform: translateY(-${distance}px);
                }
                100% {
                    transform: translateY(0px);
                }
            }
        `;
    }

    /**
     * Applies animation styles to the container.
     * @param {number} index - The index of the container.
     */
    applyStyles(index) {
        const duration = this.attributes.duration;
        this.container.style.animation = `floating-${index} ${duration}s ease infinite`;
        this.container.style.animationTimingFunction = 'ease-in-out';
    }
}