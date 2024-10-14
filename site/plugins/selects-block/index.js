panel.plugin("cookbook/selects-block", {
    blocks: {
      selects: {
        template: `
          <div>
            <p>{{ content.question }}</p>
           
          </div>
        `
      }
    }
  });