using UnityEngine;
using UnityEngine.EventSystems;
using UnityEngine.UI;
using System.Collections;

public class Nextur : MonoBehaviour {
	public int thisFieldIndex = 0;


	public void DoneEditing(string text) {
		bool shouldGoToNext = true;
		int nextField = thisFieldIndex + 1;
		if (nextField >= InputNextur.Instance.inputFields.Length) {
			nextField = 0;
			shouldGoToNext = false;
		}
		if (InputNextur.Instance.inputFields[thisFieldIndex].wasCanceled) {
			shouldGoToNext = false;
		}
		if (shouldGoToNext) {

			InputNextur.Instance.inputFields[thisFieldIndex].DeactivateInputField();
			InputNextur.Instance.inputFields[nextField].Select();
		} else {
			InputNextur.Instance.inputFields[thisFieldIndex].DeactivateInputField();
		}
	}
}
