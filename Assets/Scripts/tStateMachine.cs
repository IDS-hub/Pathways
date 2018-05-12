using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class tStateMachine : MonoBehaviour {

	public GameObject LeftPanel;

	Animator leftPanelAnimator;

	[SerializeField] Transform screen_content;
	[SerializeField] Transform popup_content;
	[SerializeField] APIManager apiManager;
	[SerializeField] bool clearPlayerPrefs;

	public static bool isLoginSuccessfull = false;

	public int sliderIndex = 0;
	private GameObject active_screen;
	private GameObject active_popup;

	// Use this for initialization
	void Start() {
		leftPanelAnimator = LeftPanel.GetComponent<Animator>();

		Screen.orientation = ScreenOrientation.Portrait;
		MoveToSelected(WindowPanels.Loading);

		if (clearPlayerPrefs)
			PlayerPrefs.DeleteAll();
	}
		
	public void EnableLeftPanel(bool isEnable) {
		if (isEnable)
			leftPanelAnimator.SetInteger("Enabled", 1);
		else
			leftPanelAnimator.SetInteger("Enabled", 0);
	}

	public void MoveToSelected(WindowPanels index) {
		LoadUI(index);
	}

	void LoadUI(WindowPanels screen) {
		//Debug.Log("LoadUI " + screen.ToString());

		CheckForActiveScreen();
		CheckForActivePopup();

		GameObject temp = Resources.Load <GameObject>(screen.ToString());
		active_screen = Instantiate(temp) as GameObject;
		active_screen.transform.SetParent(screen_content);

		RectTransform panel = (RectTransform)active_screen.transform;
		panel.transform.localPosition = Vector3.zero;
		panel.sizeDelta = Vector2.zero;

		active_screen.transform.localScale = Vector3.one;

		if (!active_screen.activeSelf)
			active_screen.SetActive(true);	
	}

	public void LoadPopupUI(WindowPanels screen) {
		//Debug.Log("LoadPopupUI " + screen.ToString());

		CheckForActivePopup();

		GameObject temp = Resources.Load <GameObject>(screen.ToString());
		active_popup = Instantiate(temp) as GameObject;
		active_popup.transform.SetParent(popup_content);

		RectTransform panel = (RectTransform)active_popup.transform;
		panel.transform.localPosition = Vector3.zero;
		panel.sizeDelta = Vector2.zero;

		active_popup.transform.localScale = Vector3.one;

		if (!active_popup.activeSelf)
			active_popup.SetActive(true);	
	}

	void CheckForActiveScreen() {
		if (active_screen != null) {
			Destroy(active_screen);
			active_screen = null;
		}
	}

	void CheckForActivePopup() {
		if (active_popup != null) {
			Destroy(active_popup);
			active_popup = null;
		}
	}

}
