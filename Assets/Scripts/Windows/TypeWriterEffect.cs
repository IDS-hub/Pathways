using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class TypeWriterEffect : MonoBehaviour {

	public static System.Action OnEndWriting;

	[TextArea(7, 10)][SerializeField] public string writeText;
	[SerializeField] Text textField;
	float writeSpeed = .03f;

	public virtual void Start() {
		AudioController.Instance.PlayTypeWriterSound();
		StartCoroutine("AnimateText");
	}

	void OnDisable(){
		AudioController.Instance.StopWriterSound();
	}
		
	IEnumerator AnimateText() {
		for (int i = 0; i < (writeText.Length + 1); i++) {
			textField.text = writeText.Substring(0, i);
			yield return new WaitForSeconds(writeSpeed);
		}

		AudioController.Instance.StopWriterSound();
		if (OnEndWriting != null)
			OnEndWriting();
	}

	public void OnClickBigButton(){
		StopCoroutine("AnimateText");
		textField.text = writeText;
		if (OnEndWriting != null)
			OnEndWriting();
	}
}
