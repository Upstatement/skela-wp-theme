import $ from 'jquery';
import Menu from './components/menu';

$(document).ready(() => {
  new Menu();

  // This is an example of how to do code splitting. The JS in this
  // referenced file will only be loaded on that page. Good for
  // when you have a large amount of JS only needed in one place
  /*
    if ($("#js-process").length > 0) {
        require.ensure(
            ["./pages/process"],
            require => {
                const Process = require("./pages/process").default;
                this.process = new Process();
            },
            "process"
        );
    }
    */
});
