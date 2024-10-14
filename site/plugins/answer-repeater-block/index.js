panel.plugin("cookbook/repeater-block", {
    blocks: {
      repeater: {
        template: `
          <div>
            <p>{{content.answer}}</p>
          </div>
        `
      }
    }
  });