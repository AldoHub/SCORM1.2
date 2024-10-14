panel.plugin("cookbook/textareas-block", {
    blocks: {
      textareas: {
        template: `
          <div>
            <p>{{ content.question }}</p>
           
          </div>
        `
      }
    }
  });