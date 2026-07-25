# Elementor Extra Motion

Elementor Extra Motion is a WordPress plugin that extends Elementor with additional motion effects for layouts, widgets, and page entrances.

## Description

This plugin adds a set of animated visual interactions to Elementor-based websites, including:
- Entrance animations:
  - Blur in / blur out
  - Fade in up / right / left / down
  - Fade in forward / backward
- Ken-Burns zoom effects for sections with configurable parameters
- Vertical parallax motion with adjustable parameters
- Floating widget motion with configurable vertical distance and movement duration

## Installation

1. Copy the plugin folder into your WordPress plugins directory at `wp-content/plugins/`.
2. Activate the plugin from the WordPress admin panel.
3. Make sure Elementor is installed and active.

## Features

- Add animated entrance effects directly from Elementor’s animation controls
- Apply Ken-Burns zoom motion to sections and containers
- Enable vertical parallax movement for a more immersive scrolling effect
- Make widgets gently float with adjustable speed and distance
- Customize each effect through Elementor settings panels

## Project Structure

- `elementor-extra-motion.php` - Main plugin bootstrap and initialization
- `admin/` - Plugin activation and settings logic
- `assets/` - CSS and JavaScript files for the motion effects
- `includes/` - Core feature implementations for animations and motions

## Usage

Once activated, the plugin automatically loads its motion features and applies the additional animation behaviors to supported Elementor elements.
