using System;
using UnityEngine.UI;
using UnityEngine;

public class AutoCompletionHint : MonoBehaviour {
	public event Action<string> OnHintClicked;

	[SerializeField] Button hintButton;
	[SerializeField] Text hintTextField;

	public void SetHintText(string hintText) {
		hintTextField.text = hintText;
	}

	void Awake() {
		if (hintButton != null)
			hintButton = GetComponent<Button>();

		hintButton.onClick.AddListener(OnHintClickedHandler);
	}

	// user clicks on a hint
	void OnHintClickedHandler() {
		if (OnHintClicked != null)
			OnHintClicked(hintTextField.text);
	}
}
