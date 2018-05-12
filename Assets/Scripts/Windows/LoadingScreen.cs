using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using DG.Tweening;

public class LoadingScreen : WindowController {

	[SerializeField] Image splash_screen;
	[SerializeField] Image loader;

	void Awake() {
		if (Application.platform == RuntimePlatform.Android) {
			splash_screen.sprite = Resources.Load <Sprite>("1080x1920");
		} else if (Application.platform == RuntimePlatform.IPhonePlayer) {
			if (Screen.currentResolution.height == 1136)
				splash_screen.sprite = Resources.Load <Sprite>("640x1136");
			else if (Screen.currentResolution.width == 750)
				splash_screen.sprite = Resources.Load <Sprite>("750x1334");
			else if (Screen.currentResolution.width == 1242 || Screen.currentResolution.width == 1080) {
				splash_screen.sprite = Resources.Load <Sprite>("1242x2208");
			} else if (Screen.currentResolution.width == 768 || Screen.currentResolution.width == (768 * 2)) {
				splash_screen.sprite = Resources.Load <Sprite>("1536x2048");
			} else
				splash_screen.sprite = Resources.Load <Sprite>("640x960");
		} else {
			splash_screen.sprite = Resources.Load <Sprite>("1080x1920");
		}
	}

	public override void Start() {
		base.Start();

		loader.rectTransform.rotation = Quaternion.Euler(new Vector3(0, 0, 0));
		loader.rectTransform.DORotate(new Vector3(0f, 0, 360f), .5f, RotateMode.FastBeyond360).SetLoops(-1, LoopType.Restart).SetEase(Ease.Linear);

		// Loading screen
		apiManager.GetAllDiagnosis((jsonV, isSuccess) => {
			if (isSuccess) {

				apiManager.WebRequestProfileShow((value, success) => {

					if (!success)
						UserInfo.AccessToken = "";

					if (success) {
						apiManager.GetAllSession((data, gotSession) => {

							// if avatar customisation data found, then the user is returning.
							if (value.GetField("avatarJsonData", "").Length == 0)
								stateMachine.MoveToSelected(WindowPanels.IntroductionWelcome);
							else
								stateMachine.MoveToSelected(WindowPanels.Home);

						}, APIManager.RESPONSE_SESSION);

					} else
						stateMachine.MoveToSelected(WindowPanels.AddDiagnoses);

				}, APIManager.RESPONSE_PROFILE_SHOW);
			}

		}, APIManager.RESPONSE_DIAGNOSIS);
	}

	void OnDisable(){
		loader.rectTransform.DOKill();
	}
}
