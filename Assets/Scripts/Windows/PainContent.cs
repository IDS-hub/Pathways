using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class PainContent : MonoBehaviour {
	public static System.Action<string> OnDeletePain;

	[SerializeField] Text pain_name;

	public void SetPainName(string name){
		pain_name.text = name;
	}

	public void OnDelete(){
		if (OnDeletePain != null)
			OnDeletePain(pain_name.text);

		Destroy(gameObject);
	}
}
