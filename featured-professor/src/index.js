import "./index.scss";
import { useSelect } from "@wordpress/data";
import { useEffect, useState } from "react";
import apiFetch from "@wordpress/api-fetch";
wp.blocks.registerBlockType("ourplugin/featured-professor", {
  title: "Professor Callout",
  description:
    "Include a short description and link to a professor of your choice",
  icon: "welcome-learn-more",
  category: "common",
  attributes: {
    profId: {
      type: "string",
      default: 1,
    },
  },
  edit: EditComponent,
  save: function () {
    return null;
  },
});
function updateTheMetaData() {
  const profsForMeta = wp.data
    .select("core/block-editor")
    .getBlocks()
    .filter((x) => x.name == "ourplugin/featured-professor")
    .map((x) => x.attributes.profId)
    .filter((x, index, arr) => {
      return arr.indexOf(x) == index;
    });
    
  console.log(profsForMeta);

  wp.data.dispatch("core/editor").editPost({
    meta: {
      featuredProfessor: profsForMeta,
    },
  });
}
function EditComponent(props) {
  const [thePreview, setThePreview] = useState("");
  useEffect(() => {
    updateTheMetaData();
    async function go() {
      const response = await apiFetch({
        path: `/featuredProfessor/v1/getHTML?profId=${props.attributes.profId}`,
        method: "GET",
      });
      setThePreview(response);
    }
    go();
  }, [props.attributes.profId]);
  useEffect(() => {
    return () => {
      updateTheMetaData();
    };
  }, []);
  const allProfs = useSelect((select) => {
    return select("core").getEntityRecords("postType", "professor", {
      per_page: -1,
    });
  });
  console.log(allProfs);
  if (allProfs == undefined) return <p>Loading...</p>;
  return (
    <div className="featured-professor-wrapper">
      <div className="professor-select-container">
        <select
          onChange={(e) => props.setAttributes({ profId: e.target.value })}
        >
          <option value="">Select a professor</option>
          {allProfs.map((prof) => {
            return (
              <option
                key={prof.id}
                selected={props.attributes.profId == prof.id}
                value={prof.id}
              >
                {prof.title.rendered}
              </option>
            );
          })}
        </select>
      </div>
      <div dangerouslySetInnerHTML={{ __html: thePreview }}></div>
    </div>
  );
}
