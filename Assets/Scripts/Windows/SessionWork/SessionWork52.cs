using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class SessionWork52 : MonoBehaviour {

	public static System.Action<bool, RuntimeAnimatorController> ShowAnimation;

	protected float AVATAR_SHOW_TIME = 14f;
	protected float AVATAR_SHOW_END = 157f;

	protected int currentSlide = 0;

	void OnEnable() {
		AudioPlayer.OnUpdateTime += OnUpdateTime;
		Reset();
	}

	void OnDisable() {
		AudioPlayer.OnUpdateTime -= OnUpdateTime;
		Reset();
	}

	void OnUpdateTime(float time, bool byUser) {
	//	Debug.Log("OnUpdateTime " + time);

		if (time >= AVATAR_SHOW_TIME && time <= AVATAR_SHOW_END) {
			if (time >= 152f && time <= AVATAR_SHOW_END)
				Show(7);
			else if (time >= 147f && time <= 151f)
				Show(6);
			else if (time >= 141f && time <= 146f)
				Show(5);
			else if (time >= 135f && time <= 140f)
				Show(4);
			else if (time >= 101f && time <= 126f)
				Show(3);
			else if (time >= 61f && time <= 99f)
				Show(2);
			else if (time >= AVATAR_SHOW_TIME && time <= 60f)
				Show(1);
			else
				Reset();
		} else
			Reset();
	}

	void Reset() {
		if (currentSlide > 0) {
			if (ShowAnimation != null)
				ShowAnimation(false, null);
			currentSlide = 0;
		}
	}

	void Show(int curr) {
		if (currentSlide != curr) {
			if (ShowAnimation != null)
				ShowAnimation(true, Resources.Load<RuntimeAnimatorController>(string.Format("session_52/{0}", curr)));
			currentSlide = curr;
		}
	}
}
