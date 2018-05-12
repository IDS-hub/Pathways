using UnityEngine;
using System.Collections;

public class UIController : MonoBehaviour {
	
	public static UIController INSTANCE = null;

	public Transform screen_content;
	public Transform popup_content;

	public enum SCREEN {
		LoginUI,
		ProfileUI,
		PopupUI
	}

	private GameObject active_screen;
	private GameObject active_popup;

	void Awake() {
		INSTANCE = this;
	}


	public void LoadUI(SCREEN screen) {
//		Debug.Log("LoadUI " + screen.ToString());

		CheckForActiveScreen();

		GameObject temp = Resources.Load <GameObject>(screen.ToString());
		active_screen = Instantiate(temp) as GameObject;
		active_screen.transform.SetParent(screen_content);

		RectTransform panel = (RectTransform)active_screen.transform;

		Vector3 position = panel.transform.localPosition;
		position.x = 0;
		position.y = 0;
		position.z = 0;
		panel.transform.localPosition = position;

		Vector2 sizeDelta = panel.sizeDelta;
		sizeDelta.x = 0;
		sizeDelta.y = 0;
		panel.sizeDelta = sizeDelta;

		active_screen.transform.localScale = Vector3.one;

		if (!active_screen.activeSelf)
			active_screen.SetActive(true);	
	}

	public GameObject LoadPopup(SCREEN screen) {
		Debug.Log("LoadPopup " + screen.ToString());

		CheckForActivePopup();

		GameObject temp = Resources.Load <GameObject>(screen.ToString());
		active_popup = Instantiate(temp) as GameObject;
		active_popup.transform.SetParent(popup_content);

		RectTransform panel = (RectTransform)active_popup.transform;

		Vector3 position = panel.transform.localPosition;
		position.x = 0;
		position.y = 0;
		position.z = 0;
		panel.transform.localPosition = position;

		Vector2 sizeDelta = panel.sizeDelta;
		sizeDelta.x = 0;
		sizeDelta.y = 0;
		panel.sizeDelta = sizeDelta;

		active_popup.transform.localScale = Vector3.one;

		if (!active_popup.activeSelf)
			active_popup.SetActive(true);	

		return active_popup;
	}

	void CheckForActiveScreen() {
		if (active_screen != null) {
			Destroy(active_screen);
			active_screen = null;
		}
	}

	public void CheckForActivePopup() {
		if (active_popup != null) {
			Destroy(active_popup);
			active_popup = null;
		}
	}
}
