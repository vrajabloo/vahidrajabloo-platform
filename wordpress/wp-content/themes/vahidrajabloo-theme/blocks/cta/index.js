/**
 * CTA Block - Gutenberg Editor Script
 */
(function (wp) {
    const { registerBlockType } = wp.blocks;
    const { useBlockProps, InspectorControls } = wp.blockEditor;
    const { PanelBody, TextControl, TextareaControl, SelectControl } = wp.components;
    const { Fragment } = wp.element;

    registerBlockType('vahidrajabloo/cta', {
        edit: function (props) {
            const { attributes, setAttributes } = props;
            const { title, description, buttonText, buttonUrl, variant } = attributes;

            const blockProps = useBlockProps({
                className: 'vr-cta vr-cta--' + variant
            });

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
                            label: 'Button Text',
                            value: buttonText,
                            onChange: (value) => setAttributes({ buttonText: value })
                        }),
                        wp.element.createElement(TextControl, {
                            label: 'Button URL',
                            value: buttonUrl,
                            onChange: (value) => setAttributes({ buttonUrl: value })
                        }),
                        wp.element.createElement(SelectControl, {
                            label: 'Style',
                            value: variant,
                            options: [
                                { label: 'Default (Light)', value: 'default' },
                                { label: 'Dark', value: 'dark' }
                            ],
                            onChange: (value) => setAttributes({ variant: value })
                        })
                    )
                ),
                wp.element.createElement(
                    'section',
                    blockProps,
                    wp.element.createElement(
                        'div',
                        { className: 'vr-cta__container' },
                        wp.element.createElement(
                            'div',
                            { className: 'vr-cta__content' },
                            wp.element.createElement('h2', { className: 'vr-cta__title' }, title),
                            wp.element.createElement('p', { className: 'vr-cta__description' }, description)
                        ),
                        wp.element.createElement(
                            'div',
                            { className: 'vr-cta__action' },
                            wp.element.createElement('span', { className: 'btn btn--primary' }, buttonText)
                        )
                    )
                )
            );
        },
        save: function () {
            return null;
        }
    });
})(window.wp);
