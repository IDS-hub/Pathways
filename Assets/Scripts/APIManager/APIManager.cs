using System;
using System.Collections;
using System.Collections.Generic;
using System.Text;
using JetBrains.Annotations;
using UnityEngine;
using UnityEngine.Networking;
using LitJson;
using System.Linq;

public class APIManager : MonoBehaviour {
	static APIManager _instance = null;
	public static Action OnProfileLoadComplete;

	public const int RESPONSE_DIAGNOSIS = 1;
	public const int RESPONSE_PROFILE_SHOW = 2;
	//public const int RESPONSE_ADD_DIAGNOSIS = 3;
	public const int RESPONSE_SESSION = 4;

	//public const string BASE_URL = "http://ec2-34-195-90-14.compute-1.amazonaws.com/Pathways/Api/";
	public const string BASE_URL = "http://ec2-35-171-140-84.compute-1.amazonaws.com/Pathways/Api/";

	public const string SIGN_IN = "WebRequestSignIn";
	public const string REGISTRATION = "WebRequestSignUp";

///	private JsonData requestJsonData = new JsonData();

	Action<MiniJsonObject,bool> responseDelegate;

	void Awake(){
		if (_instance == null) {
			_instance = this;
			DontDestroyOnLoad(gameObject);
		} else
			Destroy(gameObject);
	}

	private UnityWebRequest WebRequest(string url, string type, string access_token, bool useToken) {
		UnityWebRequest request = new UnityWebRequest(url, type);

		request.SetRequestHeader("Content-Type", "application/json");
		request.SetRequestHeader("Accept", "application/json");

		if (useToken)
			request.SetRequestHeader("Access-Token", access_token);

		return request;
	}

	IEnumerator PostProcessServer(string URL, WWWForm bodyJsonString, int responseCode = -1) {
		string converted = Encoding.UTF8.GetString(bodyJsonString.data, 0, bodyJsonString.data.Length);
		Debug.Log("json " + converted);

		UnityWebRequest www = UnityWebRequest.Post(URL, bodyJsonString);
		yield return www.Send();

		if (www.isError) {
			Debug.Log("error" + www.error);
			Popup.Instance.ShowPopup("Attention", "Please check internet connection.", null);
			if (responseDelegate != null)
				responseDelegate(null, false);
		} else {
			Debug.Log("request.downloadHandler.text " + www.downloadHandler.text);

			MiniJsonObject responseJsonData = new MiniJsonObject(www.downloadHandler.text);
			bool success = responseJsonData.GetField("success", false);

			if (success) {
				ProcessResponse(responseCode, responseJsonData);
			}

			// calling response callback
			if (responseDelegate != null) {
				responseDelegate(responseJsonData, success);
			}
		}

		www.Dispose();
	}

	//// parse response data
	void ProcessResponse(int responseCode, MiniJsonObject json) {
		switch (responseCode) {
			case RESPONSE_DIAGNOSIS:
				UserInfo.Diagnosis = json.GetJsonArray("res");
				break;
			case RESPONSE_PROFILE_SHOW:
				UserInfo.AccessToken = json.GetField("accessToken", "");
				UserInfo.FirstName = json.GetField("first_name", "");
				UserInfo.LastName = json.GetField("last_name", "");
				UserInfo.Email = json.GetField("email", "");

			//	UserInfo.IsSubscribe = json.GetField("isSubscribed", "0") == "1" ? true : false; // TODO:change
				UserInfo.IsSubscribe = true;

				UserInfo.ParseAvatar(json.GetField("avatarJsonData", ""));

				if (json.GetField("user_added_new_diagnosis", "").Length > 0) {
					string[] new_ones = json.GetField("user_added_new_diagnosis", "").Split(','); 
					UserInfo.UserAddedDiagnosis = new List<string>();
					if (new_ones.Length > 0)
						UserInfo.UserAddedDiagnosis.AddRange(new_ones);
				}

				if (json.GetField("user_added_diagnosis", "").Length > 0) {
					string[] new_ones = json.GetField("user_added_diagnosis", "").Split(','); 
					UserInfo.UserOriginalDiagnosis = new List<string>();
					for (int i = 0; i < new_ones.Length; i++) {
						UserInfo.UserOriginalDiagnosis.Add(UserInfo.GetDiagnosisNameById(new_ones[i]));
					}
				}

				TextAsset daily_dose = Resources.Load<TextAsset>("daily_dose");
				ArrayList list = daily_dose.text.arrayListFromJson();
				if (list != null && list.Count > 0)
					UserInfo.DailyDose = list[UnityEngine.Random.Range(0, list.Count)].ToString();
				else
					UserInfo.DailyDose = "";

				if (OnProfileLoadComplete != null)
					OnProfileLoadComplete();
				break;
			case RESPONSE_SESSION:
				if (UserInfo.SessionList != null)
					UserInfo.SessionList.Clear();
				MiniJsonArray array = json.GetJsonArray("res");
				List<Session> sessionData = new List<Session>();
				for (int i = 0; i < array.Count; i++) {
					sessionData.Add(new Session(array.Get(i).GetField("id", ""), array.Get(i).GetField("session_type", ""), array.Get(i).GetField("title", ""),
						array.Get(i).GetField("session_description", ""), array.Get(i).GetField("audio_url", "http://radlabs-portfolio.bh-43.webhostbox.net/Pathways3-UnderstandingPain.ogg"),
						array.Get(i).GetField("updated", ""), array.Get(i).GetField("isWatched", 0) == 1 ? true : false, 
						array.Get(i).GetField("session_summary", ""), array.Get(i).GetField("session_summary_image", "")));
				}
				UserInfo.SessionList = sessionData;

				if (OnProfileLoadComplete != null)
					OnProfileLoadComplete();
				break;
		}
	}

	#region SIGNS
	// sign in email users
	public void WebRequestSignIn(string email, string password, Action<MiniJsonObject,bool> response = null) {
		WWWForm formData = new WWWForm();
		formData.AddField("email", email);
		formData.AddField("password", password);

		responseDelegate = response;

		StartCoroutine(PostProcessServer(BASE_URL + SIGN_IN, formData));
	}

	// signout user
	public void WebRequestSignOut(Action<MiniJsonObject,bool> response = null) {
		WWWForm formData = new WWWForm();
		formData.AddField("accessToken", UserInfo.AccessToken);

		responseDelegate = response;

		StartCoroutine(PostProcessServer(BASE_URL + "WebRequestSignOut ", formData));
	}

	// storing facebook data to server
	public void WebRequestFacebookLogin(string firstname, string lastname, string fbid, Action<MiniJsonObject,bool> response = null) {
		WWWForm formData = new WWWForm();

		formData.AddField("first_name", firstname);
		formData.AddField("last_name", lastname);
		formData.AddField("facebook_id", fbid);
		formData.AddField("email", "temp@temp.com");

		responseDelegate = response;

		StartCoroutine(PostProcessServer(BASE_URL + "WebRequestProfileAuthProviders", formData));
	}

	#endregion

	#region PROFILE

	// register new user
	public void WebRequestProfileSingUp(string _first_name, string _last_name, string _email, string _password, Action<MiniJsonObject,bool> response = null) {

		WWWForm formData = new WWWForm();
		formData.AddField("first_name", _first_name);
		formData.AddField("last_name", _last_name);
		formData.AddField("email", _email);
		formData.AddField("password", _password);
		formData.AddField("confirmpassword", _password);

		responseDelegate = response;

		StartCoroutine(PostProcessServer(BASE_URL + REGISTRATION, formData));
	}

	// For profile authentication
	public void WebRequestProfileShow(Action<MiniJsonObject,bool> response, int responseCode = -1) {
		WWWForm formData = new WWWForm();
		formData.AddField("accessToken", UserInfo.AccessToken);
		responseDelegate = response;
		StartCoroutine(PostProcessServer(BASE_URL + "WebRequestProfileShow ", formData, responseCode));
	}

	// saving user avatar data to server
	public void SaveUserAvatar(string userAvatar, Action<MiniJsonObject,bool> response, int responseCode = -1) {
		WWWForm formData = new WWWForm();
		formData.AddField("accessToken", UserInfo.AccessToken);
		formData.AddField("avatarJsonData", userAvatar);
		responseDelegate = response;
		StartCoroutine(PostProcessServer(BASE_URL + "SaveUserAvatar ", formData, responseCode));
	}

	#endregion

	#region PASSWORD
	// forgot password
	public void WebRequestPasswordEdit(string _email, Action<MiniJsonObject,bool> response, int responseCode = -1) {
		WWWForm formData = new WWWForm();
		formData.AddField("email", _email);
		responseDelegate = response;
		StartCoroutine(PostProcessServer(BASE_URL + "forgetPassword", formData, responseCode));
	}

	#endregion

	#region Diagnosis

	// get all known diagnosis list
	public void GetAllDiagnosis(Action<MiniJsonObject,bool> response, int reponse = -1) {
		WWWForm formData = new WWWForm();
		responseDelegate = response;
		StartCoroutine(PostProcessServer(BASE_URL + "GetAllDiagnosis ", formData, reponse));
	}

	// add user specific diagnosis to server
	public void AddUserDiagnonis(Action<MiniJsonObject,bool> response = null) {
		WWWForm formData = new WWWForm();
		formData.AddField("accessToken", UserInfo.AccessToken);//diagnosis_request_title
		string newAdded = "";

		if (UserInfo.UserAddedDiagnosis != null) {
			for (int i = 0; i < UserInfo.UserAddedDiagnosis.Count; i++) {
				if (i == 0)
					newAdded += UserInfo.UserAddedDiagnosis[i];
				else
					newAdded += "," + UserInfo.UserAddedDiagnosis[i];
			}
		}
			
		formData.AddField("user_added_new_diagnosis", newAdded);
		formData.AddField("user_added_diagnosis", UserInfo.UserAddedDiagosisInId);
		responseDelegate = response;
		StartCoroutine(PostProcessServer(BASE_URL + "AddUserDiagnonis ", formData));
	}

	#endregion

	#region Session

	// Get all session of user
	public void GetAllSession(Action<MiniJsonObject,bool> response, int reponse = -1) {
		WWWForm formData = new WWWForm();
		formData.AddField("accessToken", UserInfo.AccessToken);
		responseDelegate = response;
		StartCoroutine(PostProcessServer(BASE_URL + "getUserSession", formData, reponse));
	}

	// play a perticuler session
	public void PlaySession(string sessionID, Action<MiniJsonObject,bool> response, int reponse = -1) {
		WWWForm formData = new WWWForm();
		formData.AddField("accessToken", UserInfo.AccessToken);
		formData.AddField("session_id", sessionID);
		responseDelegate = response;
		StartCoroutine(PostProcessServer(BASE_URL + "PlaySession", formData, reponse));
	}

	#endregion

	#region Stat
	public void CanGiveFeedback(Action<MiniJsonObject,bool> response, int reponse = -1) {
		WWWForm formData = new WWWForm();
		formData.AddField("accessToken", UserInfo.AccessToken);
		responseDelegate = response;
		StartCoroutine(PostProcessServer(BASE_URL + "CanGiveFeedback", formData, reponse));
	}

	// Add user pain to server
	public void AddUserPain(int painLevel, Action<MiniJsonObject,bool> response, int reponse = -1) {
		WWWForm formData = new WWWForm();
		formData.AddField("accessToken", UserInfo.AccessToken);
		formData.AddField("userPain", painLevel);
		responseDelegate = response;
		StartCoroutine(PostProcessServer(BASE_URL + "AddUserPain", formData, reponse));
	}

	public void GetUserStatistics(Action<MiniJsonObject,bool> response, int reponse = -1) {
		WWWForm formData = new WWWForm();
		formData.AddField("accessToken", UserInfo.AccessToken);
		responseDelegate = response;
		StartCoroutine(PostProcessServer(BASE_URL + "GetUserStatistics", formData, reponse));
	}

	#endregion

	#region Purchase

	public void DoSubscribeUser(string receiptToken, Action<MiniJsonObject,bool> response, int reponse = -1) {
		WWWForm formData = new WWWForm();
		formData.AddField("accessToken", UserInfo.AccessToken);

		if (Application.platform == RuntimePlatform.Android) 
			formData.AddField("device_type", "ANDROID");
		else if(Application.platform == RuntimePlatform.IPhonePlayer)
			formData.AddField("device_type", "IOS");

		formData.AddField("receiptToken", receiptToken);
		responseDelegate = response;
	//	StartCoroutine(PostProcessServer(BASE_URL + "SubscribeUser", formData, reponse));
		StartCoroutine(PostProcessServer(BASE_URL + "subscriptionPurchase", formData, reponse));
	}

	#endregion

	public void SendFeedBack(string feedback, Action<MiniJsonObject,bool> response, int reponse = -1){
		WWWForm formData = new WWWForm();
		formData.AddField("accessToken", UserInfo.AccessToken);
		formData.AddField("userComment", feedback);
		responseDelegate = response;
		StartCoroutine(PostProcessServer(BASE_URL + "SendUserFeedBack", formData, reponse));
	}
}

[Serializable]
public class StatData {
	public int PainLevel;
	public int Index;
	public string Name;
	public float Duration;
	public string SessionDate;

	public StatData() {
	}

	public StatData(int id, int painLevel, string name, float duration, string sessionDate) {
		Index = id;
		PainLevel = painLevel;
		Name = name;
		Duration = duration;

		DateTime convertedDate = DateTime.Parse(sessionDate);
		SessionDate = convertedDate.ToLocalTime().ToShortDateString();
	}
}

[Serializable]
public class Session {
	public string id;
	public string session_type;
	public string title;
	public string session_description;
	public string url;
	public string updatedTime;
	public bool isWatched;
	public string session_summary;
	public string session_summary_image_url;

	public Session(string id, string session_type, string title, string session_description, string url, 
		string updatedTime, bool isWatched, string session_summary, string session_summary_image_url) {
		this.id = id;
		this.session_type = session_type;
		this.title = title;
		this.session_description = session_description;
		this.url = url;
		this.updatedTime = updatedTime;
		this.isWatched = isWatched;
		this.session_summary = session_summary;
		this.session_summary_image_url = session_summary_image_url;
	}
}