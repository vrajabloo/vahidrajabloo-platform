/**
 * Features Grid Block - Gutenberg Editor Script
 */
(function (wp) {
    const { registerBlockType } = wp.blocks;
    const { useBlockProps, InspectorControls } = wp.blockEditor;
    const { PanelBody, TextControl, TextareaControl, RangeControl, Button } = wp.components;
    const { Fragment, useState } = wp.element;

    registerBlockType('vahidrajabloo/features-grid', {
        edit: function (props) {
            const { attributes, setAttributes } = props;
            const { sectionTitle, sectionSubtitle, features, columns } = attributes;

            const blockProps = useBlockProps({
                className: 'vr-features-grid vr-features-grid--cols-' + columns
            });

            const updateFeature = (index, field, value) => {
                const newFeatures = [...features];
                newFeatures[index] = { ...newFeatures[index], [field]: value };
                setAttributes({ features: newFeatures });
            };

            const addFeature = () => {
                setAttributes({
                    features: [...features, { icon: '✨', title: 'New Feature', description: 'Description here.' }]
                });
            };

            const removeFeature = (index) => {
                const newFeatures = features.filter((_, i) => i !== index);
                setAttributes({ features: newFeatures });
            };

            return wp.element.createElement(
                Fragment,
                null,
                wp.element.createElement(
                    InspectorControls,
                    null,
                    wp.element.createElement(
                        PanelBody,
                        { title: 'Section Header', initialOpen: true },
                        wp.element.createElement(TextControl, {
                            label: 'Section Title',
                            value: sectionTitle,
                            onChange: (value) => setAttributes({ sectionTitle: value })
                        }),
                        wp.element.createElement(TextareaControl, {
                            label: 'Section Subtitle',
                            value: sectionSubtitle,
                            onChange: (value) => setAttributes({ sectionSubtitle: value })
                        }),
                        wp.element.createElement(RangeControl, {
                            label: 'Columns',
                            value: columns,
                            onChange: (value) => setAttributes({ columns: value }),
                            min: 2,
                            max: 4
                        })
                    ),
                    wp.element.createElement(
                        PanelBody,
                        { title: 'Features (' + features.length + ')', initialOpen: false },
                        features.map((feature, index) => wp.element.createElement(
                            'div',
                            { key: index, style: { marginBottom: '16px', paddingBottom: '16px', borderBottom: '1px solid #ddd' } },
                            wp.element.createElement('strong', null, 'Feature ' + (index + 1)),
                            wp.element.createElement(TextControl, {
                                label: 'Icon (emoji)',
                                value: feature.icon,
                                onChange: (value) => updateFeature(index, 'icon', value)
                            }),
                            wp.element.createElement(TextControl, {
                                label: 'Title',
                                value: feature.title,
                                onChange: (value) => updateFeature(index, 'title', value)
                            }),
                            wp.element.createElement(TextareaControl, {
                                label: 'Description',
                                value: feature.description,
                                onChange: (value) => updateFeature(index, 'description', value)
                            }),
                            wp.element.createElement(Button, {
                                variant: 'link',
                                isDestructive: true,
                                onClick: () => removeFeature(index)
                            }, 'Remove')
                        )),
                        wp.element.createElement(Button, {
                            variant: 'secondary',
                            onClick: addFeature
                        }, '+ Add Feature')
                    )
                ),
                wp.element.createElement(
                    'section',
                    blockProps,
                    wp.element.createElement(
                        'div',
                        { className: 'vr-features-grid__container' },
                        (sectionTitle || sectionSubtitle) && wp.element.createElement(
                            'div',
                            { className: 'vr-features-grid__header' },
                            sectionTitle && wp.element.createElement('h2', { className: 'vr-features-grid__title' }, sectionTitle),
                            sectionSubtitle && wp.element.createElement('p', { className: 'vr-features-grid__subtitle' }, sectionSubtitle)
                        ),
                        wp.element.createElement(
                            'div',
                            { className: 'vr-features-grid__grid' },
                            features.map((feature, index) => wp.element.createElement(
                                'div',
                                { key: index, className: 'vr-features-grid__item' },
                                wp.element.createElement('span', { className: 'vr-features-grid__icon' }, feature.icon),
                                wp.element.createElement('h3', { className: 'vr-features-grid__item-title' }, feature.title),
                                wp.element.createElement('p', { className: 'vr-features-grid__item-description' }, feature.description)
                            ))
                        )
                    )
                )
            );
        },
        save: function () { return null; }
    });
})(window.wp);
