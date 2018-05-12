﻿using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class CreationHair : MonoBehaviour {
	public static System.Action<int,int> OnConfirmHairVarient;

	[SerializeField] GameObject tick;
	[SerializeField] GameObject un_tick;
	[SerializeField] GameObject haiVarient;
	[SerializeField] Image hairImage;
	[SerializeField] Image[] varients;

	void OnEnable() {
		CreationHair.OnConfirmHairVarient += ConfirmHairVarient;

		int id = int.Parse(UserInfo.UserAvatar.hairType.Split('.')[0]);
		int sub_id = int.Parse(UserInfo.UserAvatar.hairType.Split('.')[1]);

		if (haiVarient != null)
			haiVarient.SetActive(false);

		un_tick.SetActive(true);
		tick.SetActive(false);

		if (id == int.Parse(gameObject.name)) {
			un_tick.SetActive(false);
			tick.SetActive(true);

			if (sub_id > -1) {
				hairImage.sprite = varients[sub_id].sprite;
			}
		} 
	}

	void OnDisable() {
		CreationHair.OnConfirmHairVarient -= ConfirmHairVarient;
	}

	void ConfirmHairVarient(int id, int sub_id) {
		if (id == int.Parse(gameObject.name)) {
			if (haiVarient != null)
				haiVarient.SetActive(true);	
			if (id == -1 || sub_id > -1) {
				un_tick.SetActive(false);
				tick.SetActive(true);
			}
		} else {
			if (haiVarient != null)
				haiVarient.SetActive(false);
			if (id == -1 || sub_id > -1) {
				un_tick.SetActive(true);
				tick.SetActive(false);
			}
		}
	}

	public void OnClickHair() {
		if (haiVarient != null && haiVarient.activeSelf) {
			haiVarient.SetActive(false);
			return;
		}

		if (OnConfirmHairVarient != null)
			OnConfirmHairVarient(int.Parse(gameObject.name), -1);
	}

	public void OnClickHairVariant(int sub_id, Sprite sprite) {
		if (OnConfirmHairVarient != null)
			OnConfirmHairVarient(int.Parse(gameObject.name), sub_id);

		if (hairImage != null)
			hairImage.sprite = sprite;

		if (haiVarient != null && haiVarient.activeSelf) {
			haiVarient.SetActive(false);
		}
	}
}
