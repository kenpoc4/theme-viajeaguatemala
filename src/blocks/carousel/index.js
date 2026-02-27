import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';

registerBlockType( 'vguate/carousel', {
    edit: Edit,
    save: () => null, // Renderizado en servidor via render.php
} );
