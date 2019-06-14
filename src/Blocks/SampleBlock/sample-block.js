//  Import CSS.
import './style.scss';
import './editor.scss';

class SampleBlock {
  constructor(el) {
    this.registerMyBlock();
  }

  registerMyBlock() {
    const { __ } = wp.i18n;
    const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks
    const { RichText } = wp.editor;

    registerBlockType('skela/sample-block', {
      title: __('Sample Block'), // Block title.
      icon: 'nametag',
      category: 'widgets',
      keywords: [__('sample block')],
      attributes: {
        content: {
          source: 'html',
          selector: 'p',
        },
      },

      edit({ attributes, className, setAttributes }) {
        const { content } = attributes;

        function onChangeContent(newContent) {
          setAttributes({ content: newContent });
        }

        return (
          <RichText tagName="p" className={className} onChange={onChangeContent} value={content} />
        );
      },

      save({ attributes, className }) {
        const { content } = attributes;

        return <RichText.Content tagName="p" className={className} value={content} />;
      },
    });
  }
}

export default SampleBlock;
