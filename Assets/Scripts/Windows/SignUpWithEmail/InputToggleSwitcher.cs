using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class InputToggleSwitcher : MonoBehaviour {
	public static System.Action<int> OnDoneEditing;

	[SerializeField] InputField inputField;
	[SerializeField] Toggle toggle;
//	[SerializeField] int thisFieldIndex = 0;

	void Start() {
		if (inputField != null) {
			inputField.onValueChanged.AddListener(OnInputValueChanged);
		}
	}

	private void OnDestroy() {
		if (inputField != null) {
			inputField.onValueChanged.RemoveListener(OnInputValueChanged);
		}
	}

	void OnInputValueChanged(string value) {
		if (value.ToCharArray().Length > 0)
			toggle.isOn = true;
		else
			toggle.isOn = false;
	}

	public void DoneEditing(string text) {
		/*bool shouldGoToNext = true;

		int nextField = thisFieldIndex + 1;

		if (nextField >= 4) {
			nextField = 0;
			shouldGoToNext = false;
		}

		if (inputField.wasCanceled)
			shouldGoToNext = false;
		
		if (shouldGoToNext) {
			inputField.DeactivateInputField();
			//InputNextur.Instance.inputFields[nextField].Select();
			if (OnDoneEditing != null)
				OnDoneEditing(nextField);
		} else {
			inputField.DeactivateInputField();
		}*/
	}
	
}
