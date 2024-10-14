panel.plugin("cookbook/checks-block", {
    blocks: {
      radios: {
        template: `
          <div>
            <p>{{ content.question }}</p>
           
          </div>
        `
      }
    }
  });