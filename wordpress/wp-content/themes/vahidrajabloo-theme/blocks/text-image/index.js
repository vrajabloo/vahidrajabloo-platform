/**
 * Text + Image Block - Gutenberg Editor Script
 */
(function (wp) {
    const { registerBlockType } = wp.blocks;
    const { useBlockProps, InspectorControls, MediaUpload, MediaUploadCheck } = wp.blockEditor;
    const { PanelBody, TextControl, TextareaControl, SelectControl, Button } = wp.components;
    const { Fragment } = wp.element;

    registerBlockType('vahidrajabloo/text-image', {
        edit: function (props) {
            const { attributes, setAttributes } = props;
            const { title, description, buttonText, buttonUrl, imageUrl, imageId, imageAlt, imagePosition } = attributes;

            const blockProps = useBlockProps({
                className: 'vr-text-image vr-text-image--image-' + imagePosition
            });

            const onSelectImage = (media) => {
                setAttributes({
                    imageUrl: media.url,
                    imageId: media.id,
                    imageAlt: media.alt || ''
                });
            };

            return wp.element.createElement(
                Fragment,
                null,
                wp.element.createElement(
                    InspectorControls,
                    null,
                    wp.element.createElement(
                        PanelBody,
                        { title: 'Content', initialOpen: true },
                        wp.element.createElement(TextControl, {
                            label: 'Title',
                            value: title,
                            onChange: (value) => setAttributes({ title: value })
                        }),
                        wp.element.createElement(TextareaControl, {
                            label: 'Description',
                            value: description,
                            onChange: (value) => setAttributes({ description: value })
                        }),
                        wp.element.createElement(TextControl, {
                            label: 'Button Text (optional)',
                            value: buttonText,
                            onChange: (value) => setAttributes({ buttonText: value })
                        }),
                        wp.element.createElement(TextControl, {
                            label: 'Button URL',
                            value: buttonUrl,
                            onChange: (value) => setAttributes({ buttonUrl: value })
                        })
                    ),
                    wp.element.createElement(
                        PanelBody,
                        { title: 'Image', initialOpen: false },
                        wp.element.createElement(SelectControl, {
                            label: 'Image Position',
                            value: imagePosition,
                            options: [
                                { label: 'Right', value: 'right' },
                                { label: 'Left', value: 'left' }
                            ],
                            onChange: (value) => setAttributes({ imagePosition: value })
                        }),
                        wp.element.createElement(
                            MediaUploadCheck,
                            null,
                            wp.element.createElement(MediaUpload, {
                                onSelect: onSelectImage,
                                allowedTypes: ['image'],
                                value: imageId,
                                render: ({ open }) => wp.element.createElement(
                                    Button,
                                    { onClick: open, variant: imageUrl ? 'secondary' : 'primary' },
                                    imageUrl ? 'Replace Image' : 'Select Image'
                                )
                            })
                        )
                    )
                ),
                wp.element.createElement(
                    'section',
                    blockProps,
                    wp.element.createElement(
                        'div',
                        { className: 'vr-text-image__container' },
                        wp.element.createElement(
                            'div',
                            { className: 'vr-text-image__content' },
                            wp.element.createElement('h2', { className: 'vr-text-image__title' }, title || 'Add Title'),
                            wp.element.createElement('div', { className: 'vr-text-image__description' }, description || 'Add description'),
                            buttonText && wp.element.createElement(
                                'div',
                                { className: 'vr-text-image__action' },
                                wp.element.createElement('span', { className: 'btn btn--primary' }, buttonText)
                            )
                        ),
                        wp.element.createElement(
                            'div',
                            { className: 'vr-text-image__image-wrapper' },
                            imageUrl
                                ? wp.element.createElement('img', { src: imageUrl, alt: imageAlt, className: 'vr-text-image__image' })
                                : wp.element.createElement('div', { style: { background: '#e0e0e0', aspectRatio: '4/3', borderRadius: '8px' } })
                        )
                    )
                )
            );
        },
        save: function () { return null; }
    });
})(window.wp);
