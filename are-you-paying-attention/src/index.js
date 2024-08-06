import {
  TextControl,
  Flex,
  FlexBlock,
  FlexItem,
  Icon,
  Button,
  PanelBody,
  PanelRow
} from "@wordpress/components";
import { InspectorControls ,BlockControls,AlignmentToolbar,useBlockProps} from "@wordpress/block-editor";
import "./index.scss";
import React from "react";
import { ChromePicker } from "react-color";

// Lock the post if the correct answer is not set
(function () {
  let locked = false;
  wp.data.subscribe(() => {
    const results = wp.data
      .select("core/block-editor")
      .getBlocks()
      .filter((block) => {
        return (
          block.name === "ourplugin/are-you-paying-attention" &&
          block.attributes.correctAnswer === undefined
        );
      });

    if (results.length && !locked) {
      locked = true;
      wp.data.dispatch("core/editor").lockPostSaving("noanswer");
    }

    if (!results.length && locked) {
      locked = false;
      wp.data.dispatch("core/editor").unlockPostSaving("noanswer");
    }
  });
})();

wp.blocks.registerBlockType("ourplugin/are-you-paying-attention", {
  title: "Are You Paying Attention?",
  icon: "smiley",
  category: "common",
  attributes: {
    question: {
      type: "string",
    },
    answers: {
      type: "array",
      default: [""],
    },
    correctAnswer: {
      type: "number",
      default: undefined,
    },
    bgColor: { type: "string", default: "#EBEBEB" },
    theAllignments: { type: "string", default:"left"},
  },
  edit: EditComponent,
  save: function (props) {
    return null;
  },
  example:{
    title: "Are You Paying Attention?",
    description: "A simple block to ask users to pay attention to a question.",
    example: {
      attributes: {
        question: "What is the capital of France?",
        answers: ["Paris", "London", "Berlin"],
        correctAnswer: 2,
        bgColor: "#EBEBEB",
        theAllignments: "left",
      },
      content: [],
    },
  }
});

function EditComponent(props) {
  const blockProps = useBlockProps(
    {
      className:"paying-attention-edit-block",
      style:{backgroundColor: props.attributes.bgColor}
 
    }
  );
  function updateQuestion(value) {
    props.setAttributes({ question: value });
  }

  function updateAnswer(value, index) {
    const updatedAnswers = [...props.attributes.answers];
    updatedAnswers[index] = value;
    props.setAttributes({ answers: updatedAnswers });
  }

  function deleteAnswer(index) {
    const updatedAnswers = [...props.attributes.answers];
    updatedAnswers.splice(index, 1);
    props.setAttributes({ answers: updatedAnswers });

    if (props.attributes.correctAnswer === index) {
      props.setAttributes({ correctAnswer: undefined });
    } else if (props.attributes.correctAnswer > index) {
      props.setAttributes({ correctAnswer: props.attributes.correctAnswer - 1 });
    }
  }

  function addAnswer() {
    props.setAttributes({ answers: [...props.attributes.answers, ""] });
  }

  function markAsCorrect(index) {
    props.setAttributes({ correctAnswer: index });
  }

  return (
    <div
    {...blockProps}
         >
      <BlockControls>
<AlignmentToolbar value={props.attributes.theAllignments} onChange={x=> props.setAttributes({theAllignments:x})}/>
      </BlockControls>
      <InspectorControls>
        <PanelBody title="Background Color" initialOpen={true}>
          <PanelRow>
            <ChromePicker
              color={props.attributes.bgColor}
              onChangeComplete={(value) =>
                props.setAttributes({ bgColor: value.hex })
              }
              disableAlpha={true}
            />
          </PanelRow>
        </PanelBody>
      </InspectorControls>
      <TextControl
        label={"Question"}
        value={props.attributes.question}
        onChange={updateQuestion}
        style={{ fontSize: "20px" }}
      />
      <p style={{ fontSize: "13px", margin: "20px 0 8px 0" }}>Answers:</p>
      {props.attributes.answers.map((answer, index) => (
        <Flex key={index}>
          <FlexItem>
            <TextControl
              value={answer}
              onChange={(value) => updateAnswer(value, index)}
            />
          </FlexItem>
          <FlexItem>
            <Button onClick={() => markAsCorrect(index)}>
              <Icon
                className="mark-as-correct"
                icon={
                  props.attributes.correctAnswer === index
                    ? "star-filled"
                    : "star-empty"
                }
              />
            </Button>
          </FlexItem>
          <FlexItem>
            <Button
              isLink
              onClick={() => deleteAnswer(index)}
              className="attention-delete"
            >
              Delete
            </Button>
          </FlexItem>
        </Flex>
      ))}
      {props.attributes.answers.length < 5 && (
        <Button isPrimary onClick={addAnswer}>
          Add Answer
        </Button>
      )}
    </div>
  );
}