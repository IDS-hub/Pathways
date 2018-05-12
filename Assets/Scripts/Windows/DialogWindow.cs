using System;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class DialogWindow : MonoBehaviour {

	[SerializeField] InputField inputField;
	[SerializeField] Button SuccessButton;

	public RectTransform resultsParent;
	public RectTransform prefab;

	Action <string, bool> OnSuccessAction;
	Action OnCancelAction;

	float minCharsToShowHint = 2;

	List<RectTransform> hints = new List<RectTransform>();

	public void Init(string title, string body, Action <string, bool> OnSuccess, Action OnCancel) {
		//titleTextField.text = title;
		//bodyTextField.text = body;
		OnSuccessAction = OnSuccess;
		OnCancelAction = OnCancel;
	}

	void Awake() {
		SuccessButton.onClick.AddListener(OnSuccessHandler);
		inputField.onValueChanged.AddListener(OnTextValueChanged);
		SuccessButton.interactable = false;
	}

	private void OnDestroy() {
		SuccessButton.onClick.RemoveListener(OnSuccessHandler);
		//CancelButton.onClick.RemoveListener(OnCancelHandler);
		inputField.onEndEdit.RemoveListener(OnTextValueChanged);
	}

	void OnTextValueChanged(string text) {
		ClearResults();
		SuccessButton.interactable = text.Length > 0 ? true : false;
		if (text.Length < minCharsToShowHint)
			return;

		FillResults(GetResults(text));
	}

	void OnSuccessHandler() {
		//Debug.Log("inputField.text " + inputField.text);
		//bool isNew = GetResults(inputField.text).Count > 0 ? false : true;
		if (OnSuccessAction != null)
			OnSuccessAction(inputField.text, !isMatch(inputField.text));

		inputField.text = "";
		SuccessButton.interactable = false;
		//DestroyDialogWindow();
	}

	void OnCancelHandler() {
		if (OnCancelAction != null)
			OnCancelAction();

		DestroyDialogWindow();
	}

	void DestroyDialogWindow() {
		gameObject.SetActive(false);
		//Destroy(gameObject);
	}

	private void ClearResults() {
		foreach (var hint in hints) {
			if (hint != null)
				Destroy(hint.gameObject);
		}
	}

	private void FillResults(List<string> results) {
		for (int resultIndex = 0; resultIndex < results.Count; resultIndex++) {
			RectTransform child = Instantiate(prefab) as RectTransform;
			var hint = child.GetComponent<AutoCompletionHint>();
			hint.SetHintText(results[resultIndex]);
			hint.OnHintClicked += OnHintButtonClicked;

			child.SetParent(resultsParent);
			child.localScale = Vector3.one;
			hints.Add(child);
		}
	}

	private void OnHintButtonClicked(string hintText) {
		inputField.text = hintText;
		ClearResults();
	}

	private List<string> GetResults(string input) {
		var mockData = UserInfo.TotalDiagnosis;

		//return mockData.FindAll((str) => str.IndexOf(input) >= 0);
		return mockData.FindAll((str) => str.Trim().ToUpper().IndexOf(input.Trim().ToUpper()) >= 0);
	}

	bool isMatch(string input){
		for (int i = 0; i < UserInfo.TotalDiagnosis.Count; i++) {
			if (UserInfo.TotalDiagnosis[i].Trim().ToUpper() == input.Trim().ToUpper()) {
				Debug.Log(input +" is matching with " + UserInfo.TotalDiagnosis[i]);
				return true;
			}
		}

		return false;
	}
}
