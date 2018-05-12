using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using DG.Tweening;

public class TEST : MonoBehaviour {


	[SerializeField] TextAsset f;

	[SerializeField] bool doRotate = false;

	void OnEnable() {
		SessionWindow.OnRotateModel += OnRotateModel;
	}

	void OnDisable() {
		SessionWindow.OnRotateModel -= OnRotateModel;

		Debug.Log("********************************");
	}

	void Start () {
		//ArrayList list = f.text.arrayListFromJson();
		//Debug.Log(list[5].ToString());
		//transform.rotation = Quaternion.Euler(new Vector3(0,0,0));
		//transform.DORotate(new Vector3(0f, 360f, 0f), 2f, RotateMode.FastBeyond360).SetLoops(-1,LoopType.Restart).SetEase(Ease.Linear);
	}

	void OnRotateModel(bool rotate){
		transform.rotation = Quaternion.Euler(new Vector3(0,0,0));
		transform.DORotate(new Vector3(0f, 360f, 0f), 2f, RotateMode.FastBeyond360).SetLoops(-1,LoopType.Restart).SetEase(Ease.Linear);
	}

	void Update(){
		if (doRotate) {
			OnRotateModel(true);
			doRotate = false;
		}	
	}
	

}
