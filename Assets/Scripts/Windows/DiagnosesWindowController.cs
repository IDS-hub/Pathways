using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class DiagnosesWindowController : WindowController {
	[SerializeField] DialogWindow dialogWindow;
	[SerializeField] Transform content_panel;
	[SerializeField] Text helped_conditions;

	[SerializeField] GameObject firstPage;
	[SerializeField] GameObject secondPage;
	[SerializeField] GameObject saveButton;
	[SerializeField] Text addButtonText;

	List<string> diagnosisList = new List<string>();
	List<string> newDiagnosisList = new List<string>();

	//string second = "Thanks! As long as you've had pain for more than 3 months, we're sure we can help reduce or eliminate the pain you're feeling.\n As strange as it sounds, the longer you've had pain, the more we can help as your pain system will be over-sensitized.\n\n More on this soon....";
	string OnlyKnown = "As long as you've had pain for more than 3 months, we can help you too.";
	string OnlyUnKnown = "Thanks! As long as you've had pain for more than 3 months, we're sure we can help reduce or eliminate the pain you're feeling.";
	string common = "As strange as it sounds, the longer you've had pain, the more we can help as your pain system will be over-sensitized.\nMore on this soon....";

	public override void Start() {
		base.Start();

		dialogWindow.Init("Diagnoses", "The conditions I have is:", OnSuccess, OnCancel);

		diagnosisList.Clear();
		newDiagnosisList.Clear();

		if (UserInfo.UserOriginalDiagnosis != null) {
			for (int i = 0; i < UserInfo.UserOriginalDiagnosis.Count; i++)
				Reentry(UserInfo.UserOriginalDiagnosis[i], false);
		}

		if (UserInfo.UserAddedDiagnosis != null) {
			for (int i = 0; i < UserInfo.UserAddedDiagnosis.Count; i++)
				Reentry(UserInfo.UserAddedDiagnosis[i], true);
		}

		firstPage.SetActive(true);
		secondPage.SetActive(false);

		if (ProfileWindowController.OnProfile)
			saveButton.SetActive(true);
		else
			saveButton.SetActive(false);
	}

	void OnEnable() {
		PainContent.OnDeletePain += OnDeletePain;
	}

	void OnDisable() {
		PainContent.OnDeletePain -= OnDeletePain;
	}


	void OnSuccess(string data, bool isNew) {
		if (CheckForReEntry(data) || data.Length == 0)
			return;
		
		GameObject temp = Resources.Load<GameObject>("pain_content");
		temp = Instantiate(temp) as GameObject;
		temp.SetActive(true);
		temp.transform.SetParent(content_panel);

		Vector2 t = Vector2.zero;
		RectTransform rect = temp.GetComponent<RectTransform>();
		t = rect.anchoredPosition;
		t.x = 0;
		t.y = 0;
		rect.anchoredPosition = t;

		temp.transform.localScale = Vector2.one;
		temp.GetComponent<PainContent>().SetPainName(data);

		if (isNew)
			newDiagnosisList.Add(data);
		else {
			if (UserInfo.UserOriginalDiagnosis == null)
				UserInfo.UserOriginalDiagnosis = new List<string>();
			UserInfo.UserOriginalDiagnosis.Add(data);

			diagnosisList.Add(data);
		}

		saveButton.SetActive(true);
		addButtonText.text = "Add Another";
	}

	void Reentry(string data, bool isNew) {
		if (CheckForReEntry(data) || data.Length == 0)
			return;

		GameObject temp = Resources.Load<GameObject>("pain_content");
		temp = Instantiate(temp) as GameObject;
		temp.SetActive(true);
		temp.transform.SetParent(content_panel);

		Vector2 t = Vector2.zero;
		RectTransform rect = temp.GetComponent<RectTransform>();
		t = rect.anchoredPosition;
		t.x = 0;
		t.y = 0;
		rect.anchoredPosition = t;

		temp.transform.localScale = Vector2.one;
		temp.GetComponent<PainContent>().SetPainName(data);

		if (isNew)
			newDiagnosisList.Add(data);
		else {
			diagnosisList.Add(data);
		}

		saveButton.SetActive(true);

		addButtonText.text = "Add Another";
	}

	bool CheckForReEntry(string name) {
		for (int i = 0; i < diagnosisList.Count; i++) {
			if (diagnosisList[i] == name)
				return true;
		}
		for (int i = 0; i < newDiagnosisList.Count; i++) {
			if (newDiagnosisList[i] == name)
				return true;
		}
		return false;
	}

	void OnCancel() {
		Destroy(gameObject);
	}

	void OnDeletePain(string name) {
		if (diagnosisList.Contains(name))
			diagnosisList.Remove(name);

		if (newDiagnosisList.Contains(name))
			newDiagnosisList.Remove(name);

		if (UserInfo.UserOriginalDiagnosis != null && UserInfo.UserOriginalDiagnosis.Contains(name))
			UserInfo.UserOriginalDiagnosis.Remove(name);
		
		if (diagnosisList.Count == 0 && newDiagnosisList.Count == 0)
			addButtonText.text = "Add";
		
		if (!ProfileWindowController.OnProfile) {
			if (diagnosisList.Count == 0 && newDiagnosisList.Count == 0)
				saveButton.SetActive(false);
		}
	}

	public void ClickOnNext() {
		AddToText();
		diagnosisList.AddRange(newDiagnosisList);
		if (diagnosisList != null && diagnosisList.Count > 0 && !ProfileWindowController.OnProfile) {
			SaveDiagnosis();
			firstPage.SetActive(false);
			secondPage.SetActive(true);

		} else if (ProfileWindowController.OnProfile) {
			SaveDiagnosis();
			if (diagnosisList.Count == 0) {
				LetsGo();
			} else {
				firstPage.SetActive(false);
				secondPage.SetActive(true);
			}
		}
	}

	void AddToText() {
		string names = "";
		string helpedString = "";
		for (int i = 0; i < diagnosisList.Count; i++) {
			if (i == 0)
				names += diagnosisList[i];
			else
				names += ", " + diagnosisList[i];
		}

		if (diagnosisList.Count > 0 && newDiagnosisList.Count == 0)
			helpedString = string.Format("OK thanks, we've helped many people with {0}.\n\n{1}", names, OnlyKnown);
		else
			helpedString = "";

		if (newDiagnosisList.Count > 0) {
			helpedString = string.Format("{0}\n\n{1}", helpedString, OnlyUnKnown);
		}

		helpedString = string.Format("{0}\n\n{1}", helpedString, common);

		helped_conditions.text = helpedString;
	}


	/*public void ClickOnNext() {

	diagnosisList.AddRange(newDiagnosisList);
	if (diagnosisList != null && diagnosisList.Count > 0 && !ProfileWindowController.OnProfile) {

		SaveDiagnosis();

		if (UserInfo.AccessToken.Length > 0)
			stateMachine.MoveToSelected(WindowPanels.ProfileWindow);
		else
			stateMachine.MoveToSelected(WindowPanels.LoginSignUpMainWindow);
	} else if(ProfileWindowController.OnProfile) {
		SaveDiagnosis();
		Destroy(gameObject);
	}
}*/

	public void LetsGo() {
		if (diagnosisList != null && diagnosisList.Count > 0 && !ProfileWindowController.OnProfile) {
			if (UserInfo.AccessToken.Length > 0)
				stateMachine.MoveToSelected(WindowPanels.ProfileWindow);
			else
				stateMachine.MoveToSelected(WindowPanels.LoginSignUpMainWindow);
		} else if (ProfileWindowController.OnProfile) {
			stateMachine.MoveToSelected(WindowPanels.ProfileWindow);
		}
	}

	void SaveDiagnosis() {
		//UserInfo.UserOriginalDiagnosis = diagnosisList;
		UserInfo.UserAddedDiagnosis = newDiagnosisList;

		if (UserInfo.AccessToken.Length > 0)
			apiManager.AddUserDiagnonis(null);

		UserInfo.SetModel();
	}
}
