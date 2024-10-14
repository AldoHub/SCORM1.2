panel.plugin("cookbook/radios-block", {
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