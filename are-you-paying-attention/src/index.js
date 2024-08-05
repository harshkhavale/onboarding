wp.blocks.registerBlockType("ourplugin/are-you-paying-attention", {
  title: "Are You Paying Attention?",
  icon: "smiley",
  category: "common",
  attributes: {
    skyColor: {
      type: "string",
      default: "blue",
    },
    grassColor: {
      type: "string",
      default: "green",
    },
  },
  edit: function (props) {
    function updateSkyColor(e) {
      props.setAttributes({ grassColor: e.target.value });
    }
    function updateGrassColor(e) {
      props.setAttributes({ skyColor: e.target.value });
    }
    return (
      <div>
        <input
          type="text"
          placeholder="sky color"
          value={props.attributes.skyColor}
          onChange={updateSkyColor}
        />
        <input
          type="text"
          placeholder="grass color"
          onChange={updateGrassColor}
          value={props.attributes.grassColor}
        />
      </div>
    );
  },
  save: function (props) {
    return null;
  },
  deprecated: [
    {
      attributes: {
        skyColor: {
          type: "string",
          default: "blue",
        },
        grassColor: {
          type: "string",
          default: "green",
        },
      },
    },
    {
      attributes: {
        skyColor: { type: "string" },
        grassColor: { type: "string" },
      },
      save: function (props) {
        return (
          <p>
            Today the sky is {props.attributes.skyColor} and the grass is
            {props.attributes.grassColor}
          </p>
        );
      },
    },
  ],
});
