using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class SessionWork19 : SessionWork2 {

	void Start(){
		AVATAR_SHOW_TIME = 195f;
		AVATAR_SHOW_END = 285f;
	}

	public override void Reset() {
		if (Character.DoPowerPose != null)
			Character.DoPowerPose(false);

		if (Character.HideCharacter != null)
			Character.HideCharacter(true, isAvatarShow);

		isAvatarShow = false;
	}

	public override void ShowAnim() {
		if (Character.HideCharacter != null)
			Character.HideCharacter(false, true);

		if (Character.DoPowerPose != null)
			Character.DoPowerPose(true);
	}
}
