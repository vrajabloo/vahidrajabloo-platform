/**
 * Hero Block - Gutenberg Editor Script
 */
(function (wp) {
    const { registerBlockType } = wp.blocks;
    const { useBlockProps, InspectorControls, MediaUpload, MediaUploadCheck } = wp.blockEditor;
    const { PanelBody, TextControl, ToggleControl, SelectControl, Button } = wp.components;
    const { Fragment } = wp.element;

    registerBlockType('vahidrajabloo/hero', {
        edit: function (props) {
            const { attributes, setAttributes } = props;
            const {
                title,
                subtitle,
                primaryButtonText,
                primaryButtonUrl,
                secondaryButtonText,
                secondaryButtonUrl,
                imageUrl,
                imageId,
                imageAlt,
                alignment,
                showSecondaryButton
            } = attributes;

            const blockProps = useBlockProps({
                className: 'vr-hero vr-hero--' + alignment
            });

            const onSelectImage = (media) => {
                setAttributes({
                    imageUrl: media.url,
                    imageId: media.id,
                    imageAlt: media.alt || ''
                });
            };

            const onRemoveImage = () => {
                setAttributes({
                    imageUrl: '',
                    imageId: 0,
                    imageAlt: ''
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
                        { title: 'Content Settings', initialOpen: true },
                        wp.element.createElement(TextControl, {
                            label: 'Title',
                            value: title,
                            onChange: (value) => setAttributes({ title: value })
                        }),
                        wp.element.createElement(TextControl, {
                            label: 'Subtitle',
                            value: subtitle,
                            onChange: (value) => setAttributes({ subtitle: value })
                        }),
                        wp.element.createElement(SelectControl, {
                            label: 'Alignment',
                            value: alignment,
                            options: [
                                { label: 'Center', value: 'center' },
                                { label: 'Left', value: 'left' }
                            ],
                            onChange: (value) => setAttributes({ alignment: value })
                        })
                    ),
                    wp.element.createElement(
                        PanelBody,
                        { title: 'Button Settings', initialOpen: false },
                        wp.element.createElement(TextControl, {
                            label: 'Primary Button Text',
                            value: primaryButtonText,
                            onChange: (value) => setAttributes({ primaryButtonText: value })
                        }),
                        wp.element.createElement(TextControl, {
                            label: 'Primary Button URL',
                            value: primaryButtonUrl,
                            onChange: (value) => setAttributes({ primaryButtonUrl: value })
                        }),
                        wp.element.createElement(ToggleControl, {
                            label: 'Show Secondary Button',
                            checked: showSecondaryButton,
                            onChange: (value) => setAttributes({ showSecondaryButton: value })
                        }),
                        showSecondaryButton && wp.element.createElement(TextControl, {
                            label: 'Secondary Button Text',
                            value: secondaryButtonText,
                            onChange: (value) => setAttributes({ secondaryButtonText: value })
                        }),
                        showSecondaryButton && wp.element.createElement(TextControl, {
                            label: 'Secondary Button URL',
                            value: secondaryButtonUrl,
                            onChange: (value) => setAttributes({ secondaryButtonUrl: value })
                        })
                    ),
                    wp.element.createElement(
                        PanelBody,
                        { title: 'Image Settings', initialOpen: false },
                        wp.element.createElement(
                            MediaUploadCheck,
                            null,
                            wp.element.createElement(MediaUpload, {
                                onSelect: onSelectImage,
                                allowedTypes: ['image'],
                                value: imageId,
                                render: ({ open }) => wp.element.createElement(
                                    Button,
                                    {
                                        onClick: open,
                                        variant: imageUrl ? 'secondary' : 'primary',
                                        className: 'editor-media-placeholder__button'
                                    },
                                    imageUrl ? 'Replace Image' : 'Select Image'
                                )
                            })
                        ),
                        imageUrl && wp.element.createElement(
                            Button,
                            {
                                onClick: onRemoveImage,
                                variant: 'link',
                                isDestructive: true
                            },
                            'Remove Image'
                        ),
                        imageUrl && wp.element.createElement(TextControl, {
                            label: 'Image Alt Text',
                            value: imageAlt,
                            onChange: (value) => setAttributes({ imageAlt: value })
                        })
                    )
                ),
                wp.element.createElement(
                    'section',
                    blockProps,
                    wp.element.createElement(
                        'div',
                        { className: 'vr-hero__container' },
                        wp.element.createElement(
                            'div',
                            { className: 'vr-hero__content' },
                            wp.element.createElement('h1', { className: 'vr-hero__title' }, title || 'Add Title'),
                            wp.element.createElement('p', { className: 'vr-hero__subtitle' }, subtitle || 'Add subtitle'),
                            wp.element.createElement(
                                'div',
                                { className: 'vr-hero__buttons' },
                                wp.element.createElement('span', { className: 'btn btn--primary' }, primaryButtonText),
                                showSecondaryButton && wp.element.createElement('span', { className: 'btn btn--secondary' }, secondaryButtonText)
                            )
                        ),
                        imageUrl && wp.element.createElement(
                            'div',
                            { className: 'vr-hero__image-wrapper' },
                            wp.element.createElement('img', {
                                src: imageUrl,
                                alt: imageAlt,
                                className: 'vr-hero__image'
                            })
                        )
                    )
                )
            );
        },
        save: function () {
            // Server-side rendered
            return null;
        }
    });
})(window.wp);
