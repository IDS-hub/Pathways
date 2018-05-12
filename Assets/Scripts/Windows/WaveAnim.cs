using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class WaveAnim : MonoBehaviour {

	[SerializeField] RectTransform wave;
	[SerializeField] Vector2 reposX;
	[SerializeField] float targetX;
	[SerializeField] float delta;

	bool doStart = false;

	void OnEnable(){
		WaveController.OnStart += OnStart;
	}

	void OnDisable(){
		WaveController.OnStart -= OnStart;
	}
		
	void OnStart() {
		doStart = true;
	}

	void FixedUpdate(){
		if (!doStart)
			return;
		
		Vector2 temp = wave.anchoredPosition;
		temp.x -= delta;
		if (temp.x <= targetX)
			temp.x = reposX.x;

		wave.anchoredPosition = temp;
	}
}
