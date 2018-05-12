using System.Collections;
using System.Collections.Generic;
using UnityEngine.UI;
using UnityEngine;

public class QuizLocalWindow : MonoBehaviour {
	[SerializeField] GameObject correctAnswerCheckmark;
	[SerializeField] GameObject incorrectAnswerCheckmark;
	[SerializeField] Toggle trueButtonToggle;
	[SerializeField] Toggle falseButtonToggle;

	[SerializeField] Transform contentTransfrom;
	[SerializeField] Button trueButton;
	[SerializeField] Button falseButton;
	[SerializeField] Text annotationText;
	[SerializeField] Text questionText;

	QuizTask currentTask;

	public void SetTask(QuizTask task) {
		currentTask = task;
		annotationText.text = task.Annotation;
		questionText.text = task.Question;
		annotationText.gameObject.SetActive(false);
		SetButtonsInteractible(true);
		correctAnswerCheckmark.SetActive(false);
		incorrectAnswerCheckmark.SetActive(false);
		ResetToggles();
		ResetContentPosition();
	}

	// Use this for initialization
	void Start() {
		trueButton.onClick.AddListener(TrueButtonClickHandler);
		falseButton.onClick.AddListener(FalseButtonClickHandler);
	}

	private void OnDestroy() {
		trueButton.onClick.RemoveListener(TrueButtonClickHandler);
		falseButton.onClick.RemoveListener(FalseButtonClickHandler);
	}

	public void TrueButtonClickHandler() {
		trueButtonToggle.isOn = true;
		CheckAnswer(true);
	}

	public void FalseButtonClickHandler() {
		falseButtonToggle.isOn = true;
		CheckAnswer(false);
	}

	void CheckAnswer(bool userAnswer) {
		SetButtonsInteractible(false);

		if (userAnswer == currentTask.CorrectAnswer) {
			correctAnswerCheckmark.SetActive(true);
			incorrectAnswerCheckmark.SetActive(false);
		} else {
			correctAnswerCheckmark.SetActive(false);
			incorrectAnswerCheckmark.SetActive(true);
		}
		annotationText.gameObject.SetActive(true);
	}

	void SetButtonsInteractible(bool isInteractible) {
		trueButton.interactable = isInteractible;
		falseButton.interactable = isInteractible;
	}

	void ResetToggles() {
		trueButtonToggle.isOn = false;
		falseButtonToggle.isOn = false;
	}

	void ResetContentPosition() {
		contentTransfrom.position = new Vector3(contentTransfrom.position.x, 0, contentTransfrom.position.z);
	}

}
