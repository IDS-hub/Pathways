using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class ShoulderReset : MonoBehaviour {

	[SerializeField] bool left = false;
	bool dontUpdate = true;
	[SerializeField] float angle;

	void OnEnable() {
		PainSelector.OnPainSelector += CheckScreen;
	}

	void OnDisable() {
		PainSelector.OnPainSelector -= CheckScreen;
	}

	void CheckScreen(bool move, WindowPanels screen) {
		if (screen == WindowPanels.ProfileWindow || screen == WindowPanels.Session) {
			dontUpdate = false;
		} else {
			dontUpdate = true;
			transform.localRotation = Quaternion.identity;
		}
	}

	// adjusting shoulder rotation incase of blendshape change
	void LateUpdate () {
		if (dontUpdate)
			return;
		
		if (UserInfo.UserAvatar != null) {
			angle = 0f;

			if (UserInfo.UserAvatar.figureA > 100f)
				angle = angle - ((UserInfo.UserAvatar.figureA - 100f) / 20f) * 3.3f;

			if (UserInfo.UserAvatar.figure > 100f)
				angle = angle - ((UserInfo.UserAvatar.figure - 100f) / 20f) * 1.3f;

			if (UserInfo.UserAvatar.weight > 100f) {
				angle = angle - ((UserInfo.UserAvatar.weight - 100f) / 20f) * 2.3f;
			}

			Vector3 vAngle = transform.localRotation.eulerAngles;
			if(left)
				vAngle.z = vAngle.z + angle;
			else
				vAngle.z = vAngle.z - angle;
			
			transform.localRotation = Quaternion.Euler(vAngle);
		}
	}
}
