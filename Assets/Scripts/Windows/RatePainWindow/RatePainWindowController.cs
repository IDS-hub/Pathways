using System;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using System.Collections;

public class RatePainWindowController : WindowController {
	[SerializeField] Text sessionTitle;
	[SerializeField] Text warningText;
	[SerializeField] Text sessionThoughts;
	[SerializeField] Image bannerImage;
	[SerializeField] GameObject ratePainWindow;
	[SerializeField] GameObject rateAppWindow;

	int painRating = 0;

	void OnEnable() {
		sessionTitle.text = "Session: " + UserInfo.CurrentSession.title;
		warningText.enabled = false;
	}

	public override void Start() {
		base.Start();
		ratePainWindow.SetActive(false);
		rateAppWindow.SetActive(false);

		if (SessionWindow.IS_OLD_SESSION) {
			ratePainWindow.SetActive(false);
			rateAppWindow.SetActive(true);
		} else {
			// rate pain should show at a interval of 6 hours. So check from server
			Loader.Instane.ShowLoading();
			apiManager.CanGiveFeedback((json, success) => {
				Loader.Instane.RemoveLoading();
				if (success && json.GetField("cangive", false)) {
					ratePainWindow.SetActive(true);
					rateAppWindow.SetActive(false);
				} else {
					ratePainWindow.SetActive(false);
					rateAppWindow.SetActive(true);
				}
			});
		}

		sessionThoughts.text = UserInfo.CurrentSession.session_summary;

		// download banner image
		string url = UserInfo.CurrentSession.session_summary_image_url;
		Debug.Log("url is " + url);
		string[] fileArray = url.Split('/');
		string filename = "";
		if (fileArray.Length > 0) {
			filename = fileArray[fileArray.Length - 1];
			filename = WWW.UnEscapeURL(filename).Trim();
			string location = SaveLoadAudio.FilePath(filename);
			if (location.Length > 0) {
				Debug.Log("got is locaiton " + location);
				StartCoroutine("DownloadImage", new ImageProperty(location, false, filename));
			} else {
				StartCoroutine("DownloadImage", new ImageProperty(url, true, filename));
			}
		} 
		//StartCoroutine("DownloadImage", UserInfo.CurrentSession.session_summary_image_url);

	}

	public void OnGoToMainPageHandler() {
		if (painRating == -1) {
			warningText.enabled = true;
			return;
		}

		if (ratePainWindow.activeSelf) {
			// rating pain to server
			Loader.Instane.ShowLoading();
			apiManager.AddUserPain(painRating, (json, success) => {
				Loader.Instane.RemoveLoading();
				if (success) {
					stateMachine.MoveToSelected(WindowPanels.Home);
				} else {
					// user does not authenticated, logout
					Popup.Instance.ShowPopup(Constants.INVALID_SESSION_TITLE, Constants.INVALID_SESSION_DESC, () => {
						DoLogout();
					});
				}
			});
		} else
			stateMachine.MoveToSelected(WindowPanels.Home);
	}

	// user selecting the pain from drop-down list
	public void OnGotPain(int pain) {
		Debug.Log("pain is " + pain);
		painRating = pain;
	}

	IEnumerator DownloadImage(ImageProperty url) {
		WWW www = new WWW(url.url);            
		yield return www;

		if (string.IsNullOrEmpty(www.error)) {
			Texture2D texture = new Texture2D(www.texture.width, www.texture.height, TextureFormat.DXT1, false);

			// assign the downloaded image to sprite
			www.LoadImageIntoTexture(texture);
			Rect rec = new Rect(0, 0, texture.width, texture.height);
			Sprite spriteToUse = Sprite.Create(texture, rec, new Vector2(0.5f, 0.5f), 100);
			bannerImage.sprite = spriteToUse;

			if (url.isNew)
				SaveLoadAudio.SaveFile(www.bytes, url.fileName);
		}

		www.Dispose();
		www = null;
	}

	public void DoShare() {
		if (FacebookHandler._instance.LOGIN) {
			FacebookHandler._instance.FeedShare(new Uri(Constants.SHARE_LINK));
		} else {
			FacebookHandler._instance.DoLogIn((value, success) => {
				if (success) {
					FacebookHandler._instance.FeedShare(new Uri(Constants.SHARE_LINK));
				}
			});
		}
	}

	public void DoRateApp() {
		Application.OpenURL(Constants.SHARE_LINK);
	}

	public void DoFeedBack() {
		stateMachine.LoadPopupUI(WindowPanels.FeedBack);
	}
}

class ImageProperty {
	public string url;
	public bool isNew;
	public string fileName;

	public ImageProperty(string url, bool isNew, string fileName) {
		this.url = url;
		this.fileName = fileName;
		this.isNew = isNew;
	}
}
