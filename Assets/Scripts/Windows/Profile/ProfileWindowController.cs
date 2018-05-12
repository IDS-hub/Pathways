using System.Collections;
using System.Collections.Generic;
using UnityEngine.UI;
using UnityEngine;

public class ProfileWindowController : WindowController {

	public static bool OnProfile = false;
	
	[SerializeField] Text sessionNameLabel;
	[SerializeField] Text nameLabel;

	void OnEnable() {

		if (Character.HideCharacter != null)
			Character.HideCharacter(false, false);

		nameLabel.text = UserInfo.FirstName + " " + UserInfo.LastName;

		Character.OnSetModel += OnSetModel;
	}

	void OnDisable() {
		Character.OnSetModel -= OnSetModel;
	}

	public override void Start() {
		base.Start();
		LoadAvatar();
		if (!OnProfile)
			stateMachine.EnableLeftPanel(true);
		OnProfile = true;
	}

	// load avatar if not already loaded
	void LoadAvatar() {
		GameObject loadedAvatar = GameObject.FindGameObjectWithTag("Player");
		if (loadedAvatar != null)
			Debug.Log(string.Format("name {0} and tag {1}", loadedAvatar.name, loadedAvatar.tag));

		if (loadedAvatar == null) {
			loadedAvatar = Resources.Load<GameObject>(UserInfo.UserAvatar.isFemale ? "AvatarFemale" : "AvatarMale");
			loadedAvatar = Instantiate<GameObject>(loadedAvatar);
		} else if (PainSelector.OnPainSelector != null)
			PainSelector.OnPainSelector(true, WindowPanels.ProfileWindow);
	}

	void OnSetModel() {
		if (PainSelector.OnPainSelector != null)
			PainSelector.OnPainSelector(true, WindowPanels.ProfileWindow);
	}

	public void OnStartSessionClicked() {
		stateMachine.MoveToSelected(WindowPanels.Home);
	}

	public void OnEnableNavPanelButtonClicked() {
		stateMachine.EnableLeftPanel(true);
	}

	public void OnEditAvatarButtonClicked() {
		stateMachine.MoveToSelected(WindowPanels.Creation_NEW);
	}

	public void OnEditPainButtonClicked() {
		stateMachine.MoveToSelected(WindowPanels.PainSelector);
	}

	public void OnDiagnosisClicked() {
		stateMachine.MoveToSelected(WindowPanels.AddDiagnoses);
	}

}
