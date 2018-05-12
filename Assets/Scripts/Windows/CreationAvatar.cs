using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using System;
using LitJson;

public class CreationAvatar : WindowController {

	public static Action<float> OnSetEyeSize;
	public static Action<float> OnSetEyeTopDown;
	public static Action<float> OnSetHeight;
	public static Action<float> OnSetWeight;
	public static Action<float> OnSetAge;
	public static Action<float> OnSetFigure;

	public static Action<float> OnSetFace;
	public static Action<float> OnSetJaw;
	public static Action<float> OnSetChin;

	public static Action<float> OnSetNoseWidth;
	public static Action<float> OnSetNoseHeight;
	public static Action<float> OnSetNoseSize;

	public static Action<float> OnSetLipSize;
	public static Action<float> OnSetLipHeight;
	public static Action<float> OnSetLipWidth;

	//public static Action<float> OnSetNose;
	public static Action OnSave;

	[SerializeField] Text buttonLabel;
	[SerializeField] GameObject BackToNavigationPanelButtonObject;
	[SerializeField] GameObject skipObject;

	[SerializeField]private Slider ageSlider;
	[SerializeField]private Slider weightSlider;
	[SerializeField]private Slider heightSlider;
	[SerializeField]private Slider figureSlider;
	[SerializeField]private Slider eyesSizeSlider;
	[SerializeField]private Slider eyesTopSlider;

	[SerializeField]private Slider jawSizeSlider;
	[SerializeField]private Slider faceSizeSlider;
	[SerializeField]private Slider chinSizeSlider;

	[SerializeField]private Slider noseWidthSlider;
	[SerializeField]private Slider noseHeightSlider;
	[SerializeField]private Slider noseSizeSlider;

	[SerializeField]private Slider lipSizeSlider;
	[SerializeField]private Slider lipHeightSlider;
	[SerializeField]private Slider lipWidthSlider;

	const int DELTA = 10;

	GameObject loadedAvatar;

	public override void Start() {
		base.Start();
		if (!ProfileWindowController.OnProfile)
			BackToNavigationPanelButtonObject.SetActive(false);

		LoadAvatar();

		if (PainSelector.OnPainSelector != null)
			PainSelector.OnPainSelector(true, WindowPanels.Creation_NEW);

		skipObject.SetActive(!UserInfo.AvatarEditSkip);
	}

	void OnEnable() {
		Character.OnSetModel += OnSetModel;
		MaleFemaleToggle.OnToggle += OnSetGender;

		CreationTab.pressedTab = CreationTab.TAB.NONE;
	}

	void OnDisable() {
		//	if (PainSelector.OnPainSelector != null)
		//PainSelector.OnPainSelector(false, WindowPanels.Creation_NEW);
		Character.OnSetModel -= OnSetModel;
		MaleFemaleToggle.OnToggle -= OnSetGender;
		if (CreationTab.OnClick != null)
			CreationTab.OnClick(CreationTab.TAB.Age);

		CreationTab.pressedTab = CreationTab.TAB.NONE;
	}

	void LoadAvatar() {
		loadedAvatar = GameObject.FindGameObjectWithTag("Player");
		if (loadedAvatar == null) {
			loadedAvatar = Resources.Load<GameObject>(UserInfo.UserAvatar.isFemale ? "AvatarFemale" : "AvatarMale");
			loadedAvatar = Instantiate<GameObject>(loadedAvatar);
		} else
			OnSetModel();
	}

	// initialising avatar customisation values to UI
	void OnSetModel() {
		ageSlider.value = UserInfo.UserAvatar.age;

		figureSlider.value = UserInfo.UserAvatar.figure;
		weightSlider.value = UserInfo.UserAvatar.weight;
		heightSlider.value = UserInfo.UserAvatar.figureA;

		eyesSizeSlider.value = UserInfo.UserAvatar.eyes_size;
		eyesTopSlider.value = UserInfo.UserAvatar.eye_top_down_size;

		jawSizeSlider.value = UserInfo.UserAvatar.jaw_size;
		faceSizeSlider.value = UserInfo.UserAvatar.face_size;
		chinSizeSlider.value = UserInfo.UserAvatar.chin_size;

		noseWidthSlider.value = UserInfo.UserAvatar.nose_width;
		noseHeightSlider.value = UserInfo.UserAvatar.nose_height;
		noseSizeSlider.value = UserInfo.UserAvatar.nose_size;

		lipHeightSlider.value = UserInfo.UserAvatar.lip_height;
		lipSizeSlider.value = UserInfo.UserAvatar.lip_size;
		lipWidthSlider.value = UserInfo.UserAvatar.lip_width;

		if (PainSelector.OnPainSelector != null)
			PainSelector.OnPainSelector(true, WindowPanels.Creation_NEW);
	}

	// loading male/female avatar according to user preference
	public void OnSetGender(bool isFemale) {
		if (UserInfo.UserAvatar.isFemale == isFemale)
			return;

		if (PainSelector.OnPainSelector != null)
			PainSelector.OnPainSelector(false, WindowPanels.Creation_NEW);
		
		UserInfo.UserAvatar.isFemale = isFemale;
		Constants.IS_FEMALE = UserInfo.UserAvatar.isFemale;
		if (loadedAvatar != null) {
			Destroy(loadedAvatar);
			loadedAvatar = null;
		}

		loadedAvatar = Resources.Load<GameObject>(UserInfo.UserAvatar.isFemale ? "AvatarFemale" : "AvatarMale");
		loadedAvatar = Instantiate<GameObject>(loadedAvatar);
	}

	public void OnBackButtonClicked() {
		if (ProfileWindowController.OnProfile)
			stateMachine.MoveToSelected(WindowPanels.ProfileWindow);
	}

	#region eye

	public void OnPlusEyeSize() {
		eyesSizeSlider.value += DELTA;
		if (eyesSizeSlider.value > 200)
			eyesSizeSlider.value = 200;
		SetEyesSizeValue();
	}

	public void OnMinusEyeSize() {
		eyesSizeSlider.value -= DELTA;
		if (eyesSizeSlider.value < 0)
			eyesSizeSlider.value = 0;
		SetEyesSizeValue();
	}

	public void SetEyesSizeValue() {
		if (OnSetEyeSize != null)
			OnSetEyeSize(eyesSizeSlider.value);
	}

	public void OnPlusEyeTopDown() {
		eyesTopSlider.value += DELTA;
		if (eyesTopSlider.value > 200)
			eyesTopSlider.value = 200;
		SetEyesSizeValue();
	}

	public void OnMinusEyeTopDown() {
		eyesTopSlider.value -= DELTA;
		if (eyesTopSlider.value < 0)
			eyesTopSlider.value = 0;
		SetEyeTopDownValue();
	}

	public void SetEyeTopDownValue() {
		if (OnSetEyeTopDown != null)
			OnSetEyeTopDown(eyesTopSlider.value);
	}

	#endregion

	public void OnPlusAge() {
		ageSlider.value += DELTA;
		if (ageSlider.value > 200)
			ageSlider.value = 200;
		SetAgeValue();
	}

	public void OnMinusAge() {
		ageSlider.value -= DELTA;
		if (ageSlider.value < 0)
			ageSlider.value = 0;
		SetAgeValue();
	}

	public void SetAgeValue() {
		if (OnSetAge != null)
			OnSetAge(ageSlider.value);
	}

	#region Body

	//skinny-mascular index 1
	public void OnPlusFigure() {
		figureSlider.value += DELTA;
		if (figureSlider.value > 200)
			figureSlider.value = 200;
		SetFigureValue();
	}

	public void OnMinusFigure() {
		figureSlider.value -= DELTA;
		if (figureSlider.value < 0)
			figureSlider.value = 0;
		SetFigureValue();
	}

	public void SetFigureValue() {
		if (OnSetFigure != null) {
			OnSetFigure(figureSlider.value);
		}
	}

	// thin-bulky index 2
	public void OnPlusWeight() {
		weightSlider.value += DELTA;
		if (weightSlider.value > 200)
			weightSlider.value = 200;
		SetWeightValue();
	}

	public void OnMinusWeight() {
		weightSlider.value -= DELTA;
		if (weightSlider.value < 0)
			weightSlider.value = 0;
		SetWeightValue();
	}

	public void SetWeightValue() {
		if (OnSetWeight != null && loadedAvatar != null) {
			OnSetWeight(weightSlider.value);
		}
	}

	//fat index 3
	public void OnPlusHeight() {
		heightSlider.value += DELTA;
		if (heightSlider.value > 200)
			heightSlider.value = 200;
		SetHeightValue();
	}

	public void OnMinusHeight() {
		heightSlider.value -= DELTA;
		if (heightSlider.value < 0)
			heightSlider.value = 0;
		SetHeightValue();
	}

	public void SetHeightValue() {
		if (OnSetHeight != null && loadedAvatar != null) {
			OnSetHeight(heightSlider.value);
		}
	}

	#endregion

	#region Face

	public void OnPlusFaceSize() {
		faceSizeSlider.value += DELTA;
		if (faceSizeSlider.value > 200)
			faceSizeSlider.value = 200;
		SetFaceSizeValue();
	}

	public void OnMinusFaceSize() {
		faceSizeSlider.value -= DELTA;
		if (faceSizeSlider.value < 0)
			faceSizeSlider.value = 0;
		SetFaceSizeValue();
	}

	public void SetFaceSizeValue() {
		if (OnSetFace != null)
			OnSetFace(faceSizeSlider.value);
	}

	#endregion

	#region Jaw

	public void OnPlusJawSize() {
		jawSizeSlider.value += DELTA;
		if (jawSizeSlider.value > 200)
			jawSizeSlider.value = 200;
		SetJawSizeValue();
	}

	public void OnMinusJawSize() {
		jawSizeSlider.value -= DELTA;
		if (jawSizeSlider.value < 0)
			jawSizeSlider.value = 0;
		SetJawSizeValue();
	}

	public void SetJawSizeValue() {
		if (OnSetJaw != null)
			OnSetJaw(jawSizeSlider.value);
	}

	#endregion

	#region Chin

	public void OnPlusChinSize() {
		chinSizeSlider.value += DELTA;
		if (chinSizeSlider.value > 200)
			chinSizeSlider.value = 200;
		SetChinSizeValue();
	}

	public void OnMinusChinSize() {
		chinSizeSlider.value -= DELTA;
		if (chinSizeSlider.value < 0)
			chinSizeSlider.value = 0;
		SetChinSizeValue();
	}

	public void SetChinSizeValue() {
		if (OnSetChin != null)
			OnSetChin(chinSizeSlider.value);
	}

	#endregion

	#region Nose Width

	public void OnPlusNoseWidth() {
		noseWidthSlider.value += DELTA;
		if (noseWidthSlider.value > 200)
			noseWidthSlider.value = 200;
		SetNoseWidthValue();
	}

	public void OnMinusNoseWidth() {
		noseWidthSlider.value -= DELTA;
		if (noseWidthSlider.value < 0)
			noseWidthSlider.value = 0;
		SetNoseWidthValue();
	}

	public void SetNoseWidthValue() {
		if (OnSetNoseWidth != null)
			OnSetNoseWidth(noseWidthSlider.value);
	}

	#endregion

	#region Nose Height

	public void OnPlusNoseHeight() {
		noseHeightSlider.value += DELTA;
		if (noseHeightSlider.value > 200)
			noseHeightSlider.value = 200;
		SetNoseHeightValue();
	}

	public void OnMinusNoseHeight() {
		noseHeightSlider.value -= DELTA;
		if (noseHeightSlider.value < 0)
			noseHeightSlider.value = 0;
		SetNoseHeightValue();
	}

	public void SetNoseHeightValue() {
		if (OnSetNoseHeight != null)
			OnSetNoseHeight(noseHeightSlider.value);
	}

	#endregion

	#region Nose Size

	public void OnPlusNoseSize() {
		noseSizeSlider.value += DELTA;
		if (noseSizeSlider.value > 200)
			noseSizeSlider.value = 200;
		SetNoseSizeValue();
	}

	public void OnMinusNoseSize() {
		noseSizeSlider.value -= DELTA;
		if (noseSizeSlider.value < 0)
			noseSizeSlider.value = 0;
		SetNoseSizeValue();
	}

	public void SetNoseSizeValue() {
		if (OnSetNoseSize != null)
			OnSetNoseSize(noseSizeSlider.value);
	}

	#endregion

	#region Lip Size

	public void OnPlusLipSize() {
		lipSizeSlider.value += DELTA;
		if (lipSizeSlider.value > 200)
			lipSizeSlider.value = 200;
		SetLipSizeValue();
	}

	public void OnMinusLipSize() {
		lipSizeSlider.value -= DELTA;
		if (lipSizeSlider.value < 0)
			lipSizeSlider.value = 0;
		SetLipSizeValue();
	}

	public void SetLipSizeValue() {
		if (OnSetLipSize != null)
			OnSetLipSize(lipSizeSlider.value);
	}

	#endregion

	#region Lip Height

	public void OnPlusLipHeight() {
		lipHeightSlider.value += DELTA;
		if (lipHeightSlider.value > 200)
			lipHeightSlider.value = 200;
		SetLipHeightValue();
	}

	public void OnMinusLipHeight() {
		lipHeightSlider.value -= DELTA;
		if (lipHeightSlider.value < 0)
			lipHeightSlider.value = 0;
		SetLipHeightValue();
	}

	public void SetLipHeightValue() {
		if (OnSetLipHeight != null)
			OnSetLipHeight(lipHeightSlider.value);
	}

	#endregion

	#region Lip Widht

	public void OnPlusLipWidth() {
		lipWidthSlider.value += DELTA;
		if (lipWidthSlider.value > 200)
			lipWidthSlider.value = 200;
		SetLipWidthValue();
	}

	public void OnMinusLipWidth() {
		lipWidthSlider.value -= DELTA;
		if (lipWidthSlider.value < 0)
			lipWidthSlider.value = 0;
		SetLipWidthValue();
	}

	public void SetLipWidthValue() {
		if (OnSetLipWidth != null)
			OnSetLipWidth(lipWidthSlider.value);
	}

	#endregion

	public void OnTutorialSkip() {
		skipObject.SetActive(false);
		UserInfo.AvatarEditSkip = true;
	}

	public void OnClickHelp() {
	//	Popup.Instance.ShowPopup("Help!", "This is a help text", null);
		skipObject.SetActive(true);
	}

	public void SaveAndContinueButtonClicked() {
		if (UserInfo.UserAvatar != null) {
			string _requestJsonData = JsonMapper.ToJson(UserInfo.UserAvatar);
			Loader.Instane.ShowLoading();
			apiManager.SaveUserAvatar(_requestJsonData, (jsonData, success) => {
				Loader.Instane.RemoveLoading();
				if (success)
					GoToNextScreen();
				else {
					// user does not authenticated, logout
					Popup.Instance.ShowPopup(Constants.INVALID_SESSION_TITLE, Constants.INVALID_SESSION_DESC, () => {
						DoLogout();
					});
				}
			});
		} else
			GoToNextScreen();
	}

	void GoToNextScreen() {
		if (!ProfileWindowController.OnProfile)
			stateMachine.MoveToSelected(WindowPanels.PainSelector);
		else
			stateMachine.MoveToSelected(WindowPanels.ProfileWindow);
	}
}
