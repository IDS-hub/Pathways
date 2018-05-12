using UnityEngine.UI;
using System;
using UnityEngine;
using System.Collections;

public class AudioPlayer : MonoBehaviour {
	public static bool IS_AUDIO_PAUSED = false;
	public static float RESET_TIME = 2f;
	public static Action<float> ResetTimeLine;

	public event Action OnPlayerFinishWork;

	public static Action<float, bool> OnUpdateTime;
	public static Action<bool> OnPauseAudio;

	[SerializeField] AudioSource source;
	[SerializeField] GameObject playerObject;

	[SerializeField] GameObject playImageObject;
	[SerializeField] GameObject pauseImageObject;

	[SerializeField] Toggle playToggle;
	[SerializeField] AudioSlider introSlider;
	[SerializeField] Text currentProgressText;
	[SerializeField] Text audioLengthText;
	[SerializeField] Text audioClipNameText;

	[SerializeField] GameObject playButton;
	[SerializeField] GameObject backwardButton;

	AudioClip currentClip;

	private float duration = 0f;
	private float delay = 1.0f;
	//one in second we update audio time
	private float deltaTime = 0;
	private float epsilon = 1.0f;

	private bool firstTimePaused = false;
	bool isReadyToPlay = false;

	void OnEnable() {
		Time.timeScale = 1f;
		IS_AUDIO_PAUSED = false;
		ResetTimeLine += ResetTimeLineBackward;
	}

	void OnDisable() {
		IS_AUDIO_PAUSED = false;
		ResetTimeLine -= ResetTimeLineBackward;
		Time.timeScale = 1f;
	}

	public void PlayAudio() {
		InitPlayer();
	}
		
	void OnSliderValueChanged(float time) {
		// when user changing the slider by draging
		if (introSlider.isUserAction) {
			time = (float)Math.Round(time, 2);
			if (time >= currentClip.length - 2) {
				time = currentClip.length - 2;
			}
			source.time = time;
		}

		duration = currentClip.length - time;
		if (OnUpdateTime != null)
			OnUpdateTime(time, introSlider.isUserAction);
	}

	void ResetTimeLineBackward(float time) {
		duration = currentClip.length - time;
		source.time = time;
		introSlider.value = time;
	}

	public void ClickOnBackwards() {
		if (AudioPlayer.IS_AUDIO_PAUSED)
			return;
		
		float currentTime = currentClip.length - duration;
		currentTime = currentTime - 20f;
		if (currentTime < epsilon)
			currentTime = epsilon;

		duration = currentClip.length - currentTime;
		source.time = currentTime;
		introSlider.value = currentTime;

		if (OnUpdateTime != null)
			OnUpdateTime(currentTime, true);
	}

	void EnableButtons() {
		playToggle.onValueChanged.AddListener(OnPlayToggleValueChanged);
		introSlider.onValueChanged.AddListener(OnSliderValueChanged);
	}

	void InitPlayer() {
		ShowHideButtons(false);
		isReadyToPlay = false;

		string url = UserInfo.CurrentSession.url;
	//	url = "http://radlabs-portfolio.bh-43.webhostbox.net/Pathways3-UnderstandingPain.mp3";
		Debug.Log("url is " + url);
		string[] fileArray = url.Split('/');
		string filename = "";
		if (fileArray.Length > 0) {
			Loader.Instane.ShowLoading();
			filename = fileArray[fileArray.Length - 1];
			filename = WWW.UnEscapeURL(filename).Trim();
			string location = SaveLoadAudio.FilePath(filename);
			if (location.Length > 0) {
				Debug.Log("got is locaiton " + location);
				StartCoroutine("StreamAudio", new AudioProperty(location, false, filename));
			} else {
				StartCoroutine("StreamAudio", new AudioProperty(url, true, filename));
			}
		} else
			Popup.Instance.ShowPopup("Attention", "Can not load audio", null);
	}

	IEnumerator StreamAudio(AudioProperty url) {

		WWW www = new WWW(url.url);

		if (url.isNew) {
			while (!www.isDone && www.progress < 1.0f) {
//			Debug.Log(www.progress);
				Loader.Instane.ShowProgress((int)(www.progress * 100));
				yield return null;
			}
		} else
			yield return www;

		if (!string.IsNullOrEmpty(www.error)) {
			Debug.Log(www.error);
			Popup.Instance.ShowPopup("Attention", "Can not load audio", null);
		} else {
			Loader.Instane.ShowProgress(100);
			Loader.Instane.RemoveLoading();

			if (url.isNew)
				SaveLoadAudio.SaveFile(www.bytes, url.fileName);
					
			AudioClip clipa = WWWAudioExtensions.GetAudioClip(www, false, false, AudioType.OGGVORBIS);

			if (clipa.isReadyToPlay) {
				ShowHideButtons(true);
				SwitchPlayButtons(false);
				EnableButtons();
				isReadyToPlay = true;
				currentClip = clipa;
				introSlider.maxValue = currentClip.length;
				duration = currentClip.length;
				source.clip = currentClip;
				audioClipNameText.text = UserInfo.CurrentSession.title;
				audioLengthText.text = MakeStringTimeFromFloat(currentClip.length);// ((int)currentClip.length / 60).ToString() + " min";

				ResetAudio();

				IS_AUDIO_PAUSED = true;
				// pausing the game untill user start the audio
				Time.timeScale = .0000001f;
			}
		}
	}

	void ShowHideButtons(bool show) {
		if (playButton != null)
			playButton.SetActive(show);
		if (backwardButton != null)
			backwardButton.SetActive(show);

		audioClipNameText.enabled = show;
		audioLengthText.enabled = show;
	}

	public void ResetAudio() {
		source.time = 0;
		introSlider.value = introSlider.minValue;
		currentProgressText.text = "00:00";
		playToggle.isOn = false;
		firstTimePaused = false;
		source.Stop();
	}

	public void OnDestroy() {
		playToggle.onValueChanged.RemoveListener(OnPlayToggleValueChanged);
		introSlider.onValueChanged.RemoveListener(OnSliderValueChanged);
	}

	void Update() {
		if (!source.isPlaying)
			return;

		duration -= Time.deltaTime;
		if (deltaTime >= delay) {
            
			deltaTime = 0;
//			Debug.Log(duration);

			if (duration >= epsilon) {
				introSlider.value = (currentClip.length - duration);
				currentProgressText.text = MakeStringTimeFromFloat(introSlider.value);

			} else {
				ResetAudio();

				if (OnPlayerFinishWork != null)
					OnPlayerFinishWork();
			}
		} else {
			if (duration >= epsilon) {
				if (OnUpdateTime != null)
					OnUpdateTime(currentClip.length - duration, false);
			}
		}
		deltaTime += Time.deltaTime;
	}


	void SwitchPlayButtons(bool isPlay) {
		playImageObject.SetActive(isPlay);
		pauseImageObject.SetActive(!isPlay);
		introSlider.enabled = isPlay;
	}

	// user clicks on Play/Pause icon
	private void OnPlayToggleValueChanged(bool isPlay) {
		SwitchPlayButtons(isPlay);
		if (isPlay) {
			IS_AUDIO_PAUSED = false;
			Time.timeScale = 1f;
			introSlider.enabled = true;
			if (!firstTimePaused) {
				source.Play();
			} else
				source.UnPause();
		} else {
			introSlider.enabled = false;
			IS_AUDIO_PAUSED = true;
			Time.timeScale = .0000001f;
			source.Pause();
			if (!firstTimePaused)
				firstTimePaused = true;
		}
	}


	string MakeStringTimeFromFloat(float time) {
		string minutes;
		string seconds;
		if ((time / 60) < 10)
			minutes = "0" + ((int)(time / 60)).ToString();
		else
			minutes = ((int)(time / 60)).ToString();

		if ((time % 60) < 10)
			seconds = "0" + ((int)(time % 60)).ToString();
		else
			seconds = ((int)(time % 60)).ToString();

		return String.Format("{0}:{1}", minutes, seconds);
	}
}

class AudioProperty {
	public string url;
	public bool isNew;
	public string fileName;

	public AudioProperty(string url, bool isNew, string fileName) {
		this.url = url;
		this.fileName = fileName;
		this.isNew = isNew;
	}
}
