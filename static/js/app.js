/* eslint-disable */
import LazySizes from 'lazysizes';
import Unveilhooks from 'lazysizes/plugins/unveilhooks/ls.unveilhooks';
/* eslint-enable */

import { onDocumentReady } from './utils';

// Components
import Menu from './components/menu';

onDocumentReady(() => {
  new Menu();

  // This is an example of how to do code splitting. The JS in this
  // referenced file will only be loaded on that page. Good for
  // when you have a large amount of JS only needed in one place
  //
  //  if (document.querySelector('#js-process')) {
  //    import(/* webpackChunkName: "process" */ './pages/process')
  //      .then(module => {
  //        const Process = module.default;
  //        this.process = new Process();
  //      });
  //  }
});
