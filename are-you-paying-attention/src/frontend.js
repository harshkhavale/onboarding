import React, { useState, useEffect } from "react";
import ReactDOM from "react-dom";
import "./frontend.scss";

const divsToUpdate = document.querySelectorAll(".paying-attention-update-me");

divsToUpdate.forEach((div) => {
  const data = JSON.parse(div.querySelector("pre").innerText);
  console.log(data);
  const root = ReactDOM.createRoot(div);
  root.render(<Quiz {...data} />);
  div.classList.remove("paying-attention-update-me");
});

function Quiz({ question, answers, correctAnswer,bgColor,theAllignments }) {
  const [isCorrect, setIsCorrect] = useState(undefined);
  const [isCorrectDelayed, setIsCorrectDelayed] = useState(undefined);

  useEffect(() => {
    let timeout;
    if (isCorrect !== undefined) {
      timeout = setTimeout(() => setIsCorrect(undefined), 2600);
      setTimeout(() => setIsCorrectDelayed(isCorrect), 1000);
    }
    return () => clearTimeout(timeout);
  }, [isCorrect]);

  function handleAnswer(index) {
    if (index === correctAnswer) {
      setIsCorrect(true);
    } else {
      setIsCorrect(false);
    }
  }

  return (
    <div className="paying-attention-frontend" style={{backgroundColor:bgColor,textAlign:theAllignments}}>
   
      <p>{question}</p>
      <ul>
        {answers.map((answer, index) => (
          <li
            className={`${
              isCorrectDelayed !== undefined && index === correctAnswer
                ? "no-click"
                : ""
            } ${
              isCorrectDelayed !== undefined && index !== correctAnswer
                ? "fade-incorrect"
                : ""
            }`}
            onClick={
              isCorrect === undefined ? () => handleAnswer(index) : undefined
            }
            key={index}
          >
            {isCorrectDelayed !== undefined && index === correctAnswer && (
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="20"
                height="20"
                className="bi bi-check"
                viewBox="0 0 16 16"
              >
                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z" />
              </svg>
            )}
            {isCorrectDelayed !== undefined && index !== correctAnswer && (
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="20"
                height="20"
                className="bi bi-x"
                viewBox="0 0 16 16"
              >
                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
              </svg>
            )}
            {answer}
          </li>
        ))}
      </ul>
      {isCorrect === true && (
        <div className="correct-message correct-message--visible">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            className="bi bi-emoji-smile"
            viewBox="0 0 16 16"
          >
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
            <path d="M4.285 9.567a.5.5 0 0 1 .683.183A3.5 3.5 0 0 0 8 11.5a3.5 3.5 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5m4 0c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5" />
          </svg>
          <p>That is Correct!</p>
        </div>
      )}
      {isCorrect === false && (
        <div className="incorrect-message correct-message--visible">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            className="bi bi-emoji-frown"
            viewBox="0 0 16 16"
          >
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
            <path d="M4.285 12.433a.5.5 0 0 0 .683-.183A3.5 3.5 0 0 1 8 10.5c1.295 0 2.426.703 3.032 1.75a.5.5 0 0 0 .866-.5A4.5 4.5 0 0 0 8 9.5a4.5 4.5 0 0 0-3.898 2.25.5.5 0 0 0 .183.683M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5m4 0c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5" />
          </svg>
          <p>Sorry, try again!</p>
        </div>
      )}
    </div>
  );
}
