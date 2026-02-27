import { useBlockProps, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { Button, ToggleControl } from '@wordpress/components';

const MAX_IMAGES = 8;

export default function Edit( { attributes, setAttributes } ) {
    const images     = Array.isArray( attributes.images ) ? attributes.images : [ null, null, null ];
    const autoSelect = !! attributes.autoSelect;

    function addSlot() {
        if ( images.length < MAX_IMAGES ) {
            setAttributes( { images: [ ...images, null ] } );
        }
    }

    function removeSlot( index ) {
        setAttributes( { images: images.filter( ( _, i ) => i !== index ) } );
    }

    function updateImage( index, media ) {
        const next = [ ...images ];
        next[ index ] = { id: media.id, url: media.url, alt: media.alt || '' };
        setAttributes( { images: next } );
    }

    function clearImage( index ) {
        const next = [ ...images ];
        next[ index ] = null;
        setAttributes( { images: next } );
    }

    const blockProps = useBlockProps( { className: 'vguate-carousel-editor' } );

    return (
        <div { ...blockProps }>
            <p className="vguate-carousel-editor__heading">
                Carrusel de Imágenes — { images.length } imagen{ images.length !== 1 ? 'es' : '' } · muestra 3 a la vez
                { images.length < 3 && (
                    <span className="vguate-carousel-editor__warning"> (mínimo 3 recomendadas)</span>
                ) }
            </p>

            <div className="vguate-carousel-editor__grid">
                { images.map( ( image, index ) => (
                    <div key={ index } className="vguate-carousel-editor__slot">

                        <div className="vguate-carousel-editor__slot-header">
                            <span className="vguate-carousel-editor__slot-label">
                                Imagen { index + 1 }
                            </span>
                            { images.length > 1 && (
                                <button
                                    className="vguate-carousel-editor__remove-slot"
                                    onClick={ () => removeSlot( index ) }
                                    title="Eliminar este slot"
                                    aria-label={ `Eliminar imagen ${ index + 1 }` }
                                >
                                    ✕
                                </button>
                            ) }
                        </div>

                        <MediaUploadCheck>
                            <MediaUpload
                                onSelect={ ( media ) => updateImage( index, media ) }
                                allowedTypes={ [ 'image' ] }
                                value={ image?.id ?? null }
                                render={ ( { open } ) =>
                                    image?.url ? (
                                        <div className="vguate-carousel-editor__image-wrapper">
                                            <img
                                                src={ image.url }
                                                alt={ image.alt }
                                                className="vguate-carousel-editor__image"
                                            />
                                            <div className="vguate-carousel-editor__image-actions">
                                                <Button
                                                    onClick={ open }
                                                    variant="secondary"
                                                    size="small"
                                                >
                                                    Cambiar
                                                </Button>
                                                <Button
                                                    onClick={ () => clearImage( index ) }
                                                    variant="secondary"
                                                    size="small"
                                                    isDestructive
                                                >
                                                    Quitar
                                                </Button>
                                            </div>
                                        </div>
                                    ) : (
                                        <Button
                                            onClick={ open }
                                            className="vguate-carousel-editor__upload-btn"
                                            variant="secondary"
                                        >
                                            + Seleccionar imagen
                                        </Button>
                                    )
                                }
                            />
                        </MediaUploadCheck>

                    </div>
                ) ) }

                { images.length < MAX_IMAGES && (
                    <div className="vguate-carousel-editor__slot vguate-carousel-editor__slot--add">
                        <button
                            className="vguate-carousel-editor__add-btn"
                            onClick={ addSlot }
                        >
                            <span className="vguate-carousel-editor__add-icon">+</span>
                            Agregar imagen
                            <span className="vguate-carousel-editor__add-count">
                                { images.length } / { MAX_IMAGES }
                            </span>
                        </button>
                    </div>
                ) }
            </div>

            <div className="vguate-carousel-editor__options">
                <ToggleControl
                    label="Imagen seleccionada al cargar"
                    help={ autoSelect
                        ? 'La primera imagen se mostrará expandida automáticamente. El botón Cerrar estará oculto.'
                        : 'El usuario debe hacer clic en una imagen para verla ampliada.'
                    }
                    checked={ autoSelect }
                    onChange={ ( value ) => setAttributes( { autoSelect: value } ) }
                />
            </div>
        </div>
    );
}
