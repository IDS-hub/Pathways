using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class AnimatorController : MonoBehaviour {

	[SerializeField] Animator animationController;
	[SerializeField] SkinnedMeshRenderer characterRenderer;

	void OnEnable() {
		PainSelector.OnPainSelector += CheckScreen;
		Character.DoPowerPose += DoPowerPose;
	}

	void OnDisable() {
		PainSelector.OnPainSelector -= CheckScreen;
		Character.DoPowerPose -= DoPowerPose;
	}

	void CheckScreen(bool move, WindowPanels screen) {
		if (screen == WindowPanels.ProfileWindow || screen == WindowPanels.Session) {
			animationController.enabled = true;
			animationController.SetTrigger("idle");
		} else {
			animationController.enabled = false;
			SetTPose();
		}
	}

	void SetTPose() {
		if (UserInfo.AvatarHip != null) {
			Transform[] child = UserInfo.AvatarHip.GetComponentsInChildren<Transform>();
			for (int i = 0; i < child.Length; i++) {
				if (child[i].name.Contains("Pain") || child[i].name.Contains("Decal"))
					continue;
				child[i].localRotation = Quaternion.identity;
			}
			UserInfo.AvatarHip.localRotation = Quaternion.identity;
		}

		// eye's are getting close by animation, so open it again
		if (characterRenderer != null) {
			characterRenderer.SetBlendShapeWeight(78, 0);
			characterRenderer.SetBlendShapeWeight(79, 0);
		}
	}

	void DoPowerPose(bool show) {
		animationController.SetTrigger(show ? "power" : "idle");
	}
}
