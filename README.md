# com_joaktree_chart

An extension for Joaktree that provides interactive graphical genealogy visualizations within Joomla.

## Overview

com_joaktree_chart is a companion component for Joaktree.

The component does not store genealogy data itself. Instead, it uses the people, relationships, and family data managed by Joaktree and presents this information in a more visual and interactive way.

Its purpose is to make family navigation easier by displaying persons, partners, parents, children, and relationships in a graphical layout.

## Requirements

This component requires a working Joaktree installation.

Without Joaktree, com_joaktree_chart cannot function because all genealogy data is retrieved from Joaktree.

### Required Software

- Joomla 6.2.0 or later
- Joaktree
- com_joaktree_chart

## Features

### Person Navigation

- Display the selected person
- Navigate through family records
- Promote related persons to become the active person

### Partner Navigation

- Display all partners of the active person in chronological order
- Switch between partners
- Change the active relationship

### Relationship Visualization

- Display the active relationship
- Visual relationship cards
- Precision vector connection lines between related persons

### Parent Navigation

- Display parents of the active person
- Select parents to navigate through previous generations

### Child Navigation

- Display children of the active relationship in chronological birth order
- Individual/dynamic card widths based on content size while maintaining precise element spacing
- Navigate through descendants
- Automatically update when the active relationship changes

### Dynamic & Fluid Layout

- Borderless, transparent layer architecture for seamless integration with Joomla templates
- Fully responsive container that adapts height and width dynamically to eliminate unnecessary scrollbars

## Current Status

Version 1.0.2 is the latest stable release suitable for production use.

The component already supports complete navigation between:

- Parents
- Partners
- Relationships
- Children

Development will continue in future 1.x releases.

## Development History

### v0.0.5
First version that successfully installs and starts on Joomla 6.2.0.

### v0.1.2
First working implementation of person display.

### v0.2.5
Partner navigation introduced.

### v0.3.x
Relationship display introduced.

### v0.4.4
Parent navigation and relationship visualization introduced.

### v0.5.1
Child navigation introduced.

### v0.6.1
Layout corrections and visualization improvements.

### v1.0.0
First stable production-ready release.

### v1.0.1
Unified parent-relation line drawing and vertical alignment optimizations.

### v1.0.2
- Transparent and borderless layer design (`#parents-layer`, `#main-layer`, `#children-layer`).
- Fluid container height adaptation to remove redundant vertical scrollbars.
- Dynamic individual card widths for children based on content.
- Verified chronological display sequences for partners and children.

## Project Goals

The long-term goal of the project is to provide a modern graphical navigation layer for Joaktree family data while remaining fully integrated with Joomla.

## Author

Van Leeuwen Webdesign 
(Frits van Leeuwen)

GitHub: Frits1969