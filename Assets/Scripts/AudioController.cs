using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class AudioController : MonoBehaviour {
	public static AudioController Instance = null;

	[SerializeField] AudioSource source;
	[SerializeField] AudioClip typeWriterClip;
	[SerializeField]float writeSpeed = .2f;

	void Awake(){
		Instance = this;
	}
	
	public void PlayTypeWriterSound(){
		//StopCoroutine("AnimateSound");
		//StartCoroutine("AnimateSound");
	}

	public void StopWriterSound(){
		//StopCoroutine("AnimateSound");
	}

	IEnumerator AnimateSound() {
		while (true) {
			source.PlayOneShot(typeWriterClip);
			yield return new WaitForSeconds(writeSpeed);
		}
	}
}
