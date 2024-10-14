panel.plugin("cookbook/texts-block", {
    blocks: {
      texts: {
        template: `
          <div>
            <p>{{ content.question }}</p>
           
          </div>
        `
      }
    }
  });