using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class CreationTab : MonoBehaviour {
	public static System.Action<TAB> OnClick;

	public enum TAB {
		NONE,
		Age,
		Figure,
		Skintones,
		Hairstyle,
		Eyes,
		Face,
		Nose,
		Lips
	}

	[SerializeField] TAB ownTab;
	[SerializeField] GameObject showObject;
	[SerializeField] GameObject femaleObject;
	[SerializeField] Text writeText;
	[SerializeField] GameObject line;
	[SerializeField] Color active, inactive;

	public static TAB pressedTab;

	void OnEnable() {
		OnClick += TabPressed;
		MaleFemaleToggle.OnToggle += SetToggle;
	}

	void OnDisable() {
		OnClick -= TabPressed;
		MaleFemaleToggle.OnToggle -= SetToggle;
	}

	void Start() {
		if (ownTab == TAB.Figure) {
			OnClickTab();
		}
	}

	public void OnClickTab() {
		if (OnClick != null)
			OnClick(ownTab);
	}

	void SetToggle(bool flag) {
		if (pressedTab != null && ownTab == pressedTab && pressedTab == TAB.Hairstyle)
			TabPressed(ownTab);
	}

	void TabPressed(TAB tab) {
		pressedTab = tab;
		if (tab == ownTab) {
			line.SetActive(true);
			writeText.color = active;

			if (femaleObject != null)
				femaleObject.SetActive(false);
			showObject.SetActive(true);

			if (tab == TAB.Hairstyle) {
				Debug.Log("Constants.IS_FEMALE " + Constants.IS_FEMALE);
				if (Constants.IS_FEMALE) {
					showObject.SetActive(false);
					femaleObject.SetActive(true);
				}
			}
		} else {
			line.SetActive(false);
			writeText.color = inactive;
			showObject.SetActive(false);
			if (femaleObject != null)
				femaleObject.SetActive(false);
		}
	}
}
