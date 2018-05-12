using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class SessionWork60 : MonoBehaviour {

	public static System.Action<bool, RuntimeAnimatorController> ShowAnimation;

	protected float AVATAR_SHOW_TIME = 20f;
	protected float AVATAR_SHOW_END = 52f;

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
		//Debug.Log("OnUpdateTime " + time);

		if (time >= AVATAR_SHOW_TIME && time <= AVATAR_SHOW_END) {
			if (time >= 47f && time <= AVATAR_SHOW_END)
				Show(4);
			else if (time >= 40f && time <= 45f)
				Show(3);
			else if (time >= 31f && time <= 38f)
				Show(2);
			else if (time >= AVATAR_SHOW_TIME && time <= 29f)
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
				ShowAnimation(true, Resources.Load<RuntimeAnimatorController>(string.Format("session_60/{0}", curr)));
			currentSlide = curr;
		}
	}
}
