using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class SessionWork44 : MonoBehaviour {

	public static System.Action<bool, Sprite> ShowImage;

	protected float AVATAR_SHOW_TIME = 18f;
	protected float AVATAR_SHOW_END = 79f;

	float SLIDE_1 = 18f;
	float SLIDE_2 = 25f;
	float SLIDE_3 = 31f;
	float SLIDE_4 = 38f;
	float SLIDE_5 = 45f;
	float SLIDE_6 = 52f;
	float SLIDE_7 = 59f;
	float SLIDE_8 = 66f;
	float SLIDE_9 = 73f;

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
			if (time >= SLIDE_9 && time <= SLIDE_9 + 6)
				Show(9);
			else if (time >= SLIDE_8 && time <= SLIDE_8 + 6)
				Show(8);
			else if (time >= SLIDE_7 && time <= SLIDE_7 + 6)
				Show(7);
			else if (time >= SLIDE_6 && time <= SLIDE_6 + 6)
				Show(6);
			else if (time >= SLIDE_5 && time <= SLIDE_5 + 6)
				Show(5);
			else if (time >= SLIDE_4 && time <= SLIDE_4 + 6)
				Show(4);
			else if (time >= SLIDE_3 && time <= SLIDE_3 + 6)
				Show(3);
			else if (time >= SLIDE_2 && time <= SLIDE_2 + 5)
				Show(2);
			else if (time >= SLIDE_1 && time <= SLIDE_1 + 6)
				Show(1);
			else
				Reset();
		} else
			Reset();
	}

	public virtual void Reset() {
		if (currentSlide > 0) {
			if (ShowImage != null)
				ShowImage(false, null);
			currentSlide = 0;
		}
	}

	public virtual void Show(int curr) {
		if (currentSlide != curr) {
			if (ShowImage != null)
				ShowImage(true, Resources.Load<Sprite>(string.Format("session_44/{0}", curr)));
			currentSlide = curr;
		}
	}
}
