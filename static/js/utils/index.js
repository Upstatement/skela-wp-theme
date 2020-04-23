/**
 * Event listener for when the document is ready. This serves as replacement for
 * JQuery's `$(document).ready()` function.
 *
 * @see http://youmightnotneedjquery.com/#ready
 *
 * @param {Function} callback the function to call when the DOM is ready
 */
export const onDocumentReady = callback => {
  if (document.readyState !== 'loading') {
    callback();
  } else {
    document.addEventListener('DOMContentLoaded', callback, { once: true });
  }
};
