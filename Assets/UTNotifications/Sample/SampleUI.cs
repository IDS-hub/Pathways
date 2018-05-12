using UnityEngine;
using System;
using System.Collections;
using System.Collections.Generic;
using System.Runtime.InteropServices;

namespace UTNotifications
{
    /// <summary>
    /// The sample showing how to use different <c>UTNotifications</c> features.
    /// </summary>
    public class SampleUI : MonoBehaviour
    {
    //public
        /// <summary>
        /// Shows how you can initialize the UTNotifications
        /// </summary>
        public void Start()
        {
            //Please see the API Reference for the detailed information: Assets/UTNotifications/Documentation/API.Reference.html

            Manager notificationsManager = Manager.Instance;

            notificationsManager.OnSendRegistrationId += SendRegistrationId;
            notificationsManager.OnNotificationClicked += OnNotificationClicked;
            notificationsManager.OnNotificationsReceived += OnNotificationsReceived;    //Let's handle incoming notifications (not only push ones)

            if (string.IsNullOrEmpty(m_webServerAddress))
            {
                m_webServerAddress = PlayerPrefs.GetString(m_webServerAddressOptionName, "");
            }

            if (!string.IsNullOrEmpty(m_webServerAddress))
            {
                Initialize();
            }
        }

        /// <summary>
        /// Draws the sample UI.
        /// </summary>
        public void OnGUI()
        {
            int height = Screen.height / 10;
            int offsetX = 8;
            int offsetY = height / 16;
            int offsetTitle = 32;

            if (!ShowReceivedNotification(true, height, offsetX, offsetY, offsetTitle) && !ShowReceivedNotification(false, height, offsetX, offsetY, offsetTitle))
            {
                ShowMenu(height, offsetX, offsetY, offsetTitle);
            }
        }

    //protected
        /// <summary>
        /// Shows the received/clicked notification.
        /// </summary>
        /// <returns><c>true</c>, if received notification was shown, <c>false</c> otherwise.</returns>
        protected bool ShowReceivedNotification(bool clicked, int height, int offsetX, int offsetY, int offsetTitle)
        {
            ReceivedNotification notification;
            if (clicked)
            {
                if (m_clickedNotification != null)
                {
                    notification = m_clickedNotification;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                if (m_receivedNotifications.Count > 0)
                {
                    notification = m_receivedNotifications[0];
                }
                else
                {
                    return false;
                }
            }

            const int itemsCount = 3;

            GUI.Box(new Rect(offsetX, (Screen.height - ((height + offsetY) * itemsCount + offsetTitle)) / 2, Screen.width - offsetX * 2, (height + offsetY) * itemsCount + offsetTitle), clicked ? m_notificationClickedTitle : m_notificationReceivedTitle);
            int y = (Screen.height - ((height + offsetY) * itemsCount + offsetTitle)) / 2 + offsetTitle;
            
            string userData = "";
            if (notification.userData != null)
            {
                foreach (KeyValuePair<string, string> it in notification.userData)
                {
                    userData += it.Key + "=" + it.Value + " ";
                }
            }
            
            if (GUI.Button(new Rect(offsetX * 2, y, Screen.width - offsetX * 4, height), (string.IsNullOrEmpty(notification.notificationProfile) ? "" : "Profile: " + notification.notificationProfile + "\n") + notification.title + "\n" + notification.text + (string.IsNullOrEmpty(userData) ? "" : "\n" + userData) + "\n" + m_clickToHide))
            {
                HideNotification(notification.id);
                if (clicked)
                {
                    m_clickedNotification = null;
                }
                else
                {
                    m_receivedNotifications.RemoveAt(0);
                }
            }
            y += height + offsetY;
            
            if (GUI.Button(new Rect(offsetX * 2, y, Screen.width - offsetX * 4, height), m_hideAllText))
            {
                m_clickedNotification = null;
                HideAll();
            }
            y += height + offsetY;
            
            if (GUI.Button(new Rect(offsetX * 2, y, Screen.width - offsetX * 4, height), m_cancelAllText))
            {
                m_clickedNotification = null;
                CancelAll();
            }
            y += height + offsetY;

            return true;
        }

        /// <summary>
        /// Shows the sample menu.
        /// </summary>
        protected void ShowMenu(int height, int offsetX, int offsetY, int offsetTitle)
        {
            GUI.Box(new Rect(offsetX, offsetX, Screen.width - offsetX * 2, Screen.height - offsetX * 2), m_title);
            int y = offsetY + offsetTitle;
            
            string address = GUI.TextField(new Rect(offsetX * 2, y, Screen.width - offsetX * 4, height), m_webServerAddress);
            if (address != m_webServerAddress)
            {
                m_webServerAddress = address;
                PlayerPrefs.SetString(m_webServerAddressOptionName, m_webServerAddress);
            }
            y += height + offsetY;
            
            if (string.IsNullOrEmpty(m_webServerAddress))
            {
                GUI.Label(new Rect(offsetX * 2, y, Screen.width - offsetX * 4, height), m_pleaseEnterWebServerAddress);
                return;
            }
            
            if (GUI.Button(new Rect(offsetX * 2, y, Screen.width - offsetX * 4, height), m_notifyAllText))
            {
                NotifyAll();
            }
            y += height + offsetY;
            
            if (GUI.Button(new Rect(offsetX * 2, y, Screen.width - offsetX * 4, height), m_initializeText))
            {
                Initialize();
            }
            y += height + offsetY;
            
            if (GUI.Button(new Rect(offsetX * 2, y, Screen.width - offsetX * 4, height), m_localNotificationText))
            {
                CreateLocalNotification();
            }
            y += height + offsetY;
            
            if (GUI.Button(new Rect(offsetX * 2, y, Screen.width - offsetX * 4, height), m_scheduledNotificationText))
            {
                CreateScheduledNotifications();
            }
            y += height + offsetY;
            
            if (GUI.Button(new Rect(offsetX * 2, y, Screen.width - offsetX * 4, height), m_cancelRepeatingNotificationText))
            {
                CancelRepeatingScheduledNotification();
            }
            y += height + offsetY;
            
            if (GUI.Button(new Rect(offsetX * 2, y, Screen.width - offsetX * 4, height), m_incrementBadgeText))
            {
                IncrementBadge();
            }
            y += height + offsetY;
            
            if (GUI.Button(new Rect(offsetX * 2, y, Screen.width - offsetX * 4, height), m_cancelAllText))
            {
                CancelAll();
            }
            y += height + offsetY;
            
            if (GUI.Toggle(new Rect(offsetX * 2, y, Screen.width - offsetX * 4, height), m_notificationsEnabled, m_notificationsEnabledText) != m_notificationsEnabled)
            {
                SetNotificationsEnabled(!m_notificationsEnabled);
            }
            y += height + offsetY;
        }
        
        /// <summary>
        /// Initialize the <c>Manager</c>.
        /// </summary>
        protected void Initialize()
        {
            //We would like to handle incoming notifications and don't want to increment the push notifications id
            Manager notificationsManager = Manager.Instance;
            bool result = notificationsManager.Initialize(true, 0, false);
            Debug.Log("UTNotifications Initialize: " + result);

            m_notificationsEnabled = notificationsManager.NotificationsEnabled();
        }

        /// <summary>
        /// Creates the local notification.
        /// </summary>
        protected void CreateLocalNotification()
        {
            Dictionary<string, string> userData = new Dictionary<string, string>();
            userData.Add("user", "data");
            Manager.Instance.PostLocalNotification("Local", "Notification [id=" + LocalNotificationId + "]", LocalNotificationId, userData, "demo_notification_profile");
        }

        /// <summary>
        /// Creates 2 scheduled notifications: single one and repeating one.
        /// </summary>
        protected void CreateScheduledNotifications()
        {
            Manager.Instance.ScheduleNotification(15, "Scheduled", "Notification [id=" + ScheduledNotificationId + "]", ScheduledNotificationId, null, "demo_notification_profile");

            Dictionary<string, string> userData = new Dictionary<string, string>();
            userData.Add("user", "data");
            Manager.Instance.ScheduleNotificationRepeating(DateTime.Now.AddSeconds(5), 25, "Scheduled Repeating", "Notification [id=" + RepeatingNotificationId + "]", RepeatingNotificationId, userData, "demo_notification_profile");
        }

        /// <summary>
        /// Cancels the previously created repeating scheduled notification.
        /// </summary>
        protected void CancelRepeatingScheduledNotification()
        {
            Manager.Instance.CancelNotification(RepeatingNotificationId);
        }

        /// <summary>
        /// Increments the app icon badge value (iOS only).
        /// </summary>
        protected void IncrementBadge()
        {
            Manager.Instance.SetBadge(Manager.Instance.GetBadge() + 1);
        }

        /// <summary>
        /// Cancels all the previously created notifications.
        /// </summary>
        protected void CancelAll()
        {
            Manager.Instance.CancelAllNotifications();
            Manager.Instance.SetBadge(0);
            m_receivedNotifications.Clear();
        }

        /// <summary>
        /// Enables/disables notifications.
        /// </summary>
        protected void SetNotificationsEnabled(bool enabled)
        {
            Manager.Instance.SetNotificationsEnabled(enabled);
            m_notificationsEnabled = Manager.Instance.NotificationsEnabled();
        }

        /// <summary>
        /// Hides the specified notification.
        /// </summary>
        protected void HideNotification(int id)
        {
            Manager.Instance.HideNotification(id);
        }

        /// <summary>
        /// Hides all the notifications.
        /// </summary>
        protected void HideAll()
        {
            Manager.Instance.HideAllNotifications();
            m_receivedNotifications.Clear();
        }

        /// <summary>
        /// A wrapper for the <c>SendRegistrationId(string userId, string providerName, string registrationId)</c> coroutine
        /// </summary>
        protected void SendRegistrationId(string providerName, string registrationId)
        {
            string userId = SystemInfo.deviceUniqueIdentifier;
            StartCoroutine(SendRegistrationId(userId, providerName, registrationId));
        }

        /// <summary>
        /// Sends the received push notifications registrationId to the demo server
        /// </summary>
        protected IEnumerator SendRegistrationId(string userId, string providerName, string registrationId)
        {
            if (string.IsNullOrEmpty(m_webServerAddress))
            {
                m_initializeText = m_initializeTextOriginal + "\nUnable to send the registrationId: please fill the running demo server address";
                yield break;
            }

            m_initializeText = m_initializeTextOriginal + "\nSending registrationId...\nPlease make sure the example server is running as " + m_webServerAddress;

            WWWForm wwwForm = new WWWForm();
            
            wwwForm.AddField("uid", userId);
            wwwForm.AddField("provider", providerName);
            wwwForm.AddField("id", registrationId);
            
            WWW www = new WWW(m_webServerAddress + "/register", wwwForm);
            yield return www;

            if (www.error != null)
            {
                m_initializeText = m_initializeTextOriginal + "\n" + www.error + " " + www.text;
            }
            else
            {
                m_initializeText = m_initializeTextOriginal + "\n" + www.text;
            }
        }

        /// <summary>
        /// A wrapper for the <c>NotifyAll(string title, string text, string notificationProfile)</c> coroutine.
        /// </summary>
        protected void NotifyAll()
        {
            StartCoroutine(NotifyAll("Hello!", "From " + SystemInfo.deviceModel, "demo_notification_profile"));
        }

        /// <summary>
        /// Requests the demo server to notify all the registered devices with push notifications.
        /// </summary>
        protected IEnumerator NotifyAll(string title, string text, string notificationProfile)
        {
            m_notifyAllText = m_notifyAllTextOriginal + "\nSending...\nPlease make sure the example server is running as " + m_webServerAddress;

            title = WWW.EscapeURL(title);
            text = WWW.EscapeURL(text);

            string noCache = "&_NO_CACHE=" + UnityEngine.Random.value;

            WWW www = new WWW(m_webServerAddress + "/notify?title=" + title + "&text=" + text + (string.IsNullOrEmpty(notificationProfile) ? "" : "&notification_profile=" + notificationProfile) + noCache);
            yield return www;

            if (www.error != null)
            {
                m_notifyAllText = m_notifyAllTextOriginal + "\n" + www.error + " " + www.text;
            }
            else
            {
                m_notifyAllText = m_notifyAllTextOriginal + "\n" + www.text;
            }
        }

        /// <summary>
        /// Handles click on a notification.
        /// </summary>
        protected void OnNotificationClicked(ReceivedNotification notification)
        {
            m_clickedNotification = notification;
        }

        /// <summary>
        /// Handles the received notifications.
        /// </summary>
        protected void OnNotificationsReceived(IList<ReceivedNotification> receivedNotifications)
        {
            m_receivedNotifications.AddRange(receivedNotifications);
        }

        /// <summary>
        /// Address of the running demo server. You can replace the default value by your demo server address (f.e. <c>"http://192.168.2.102:8080"</c>).
        /// </summary>
        protected string m_webServerAddress = "";

        protected const int LocalNotificationId = 1;
        protected const int ScheduledNotificationId = 2;
        protected const int RepeatingNotificationId = 3;

    //private
        private const string m_title = "UTNotifications Sample";
        private const string m_notificationClickedTitle = "Notification Clicked";
        private const string m_notificationReceivedTitle = "Notification Received";
        private const string m_pleaseEnterWebServerAddress = "Please enter the running demo server address above. F.e. http://192.168.2.102:8080";
        private const string m_initializeTextOriginal = "Initialize";
        private string m_initializeText = m_initializeTextOriginal;
        private const string m_notifyAllTextOriginal = "Notify all registered devices";
        private string m_notifyAllText = m_notifyAllTextOriginal;
        private const string m_localNotificationText = "Create Local Notification";
        private const string m_incrementBadgeText = "Increment the badge number (iOS only)";
        private const string m_scheduledNotificationText = "Create Scheduled Notifications";
        private const string m_cancelRepeatingNotificationText = "Cancel Repeating Notification";
        private const string m_hideAllText = "Hide All Notifications";
        private const string m_cancelAllText = "Cancel All Notifications\n(Also resets the badge number on iOS)";
        private const string m_notificationsEnabledText = "Notifications Enabled";
        private const string m_webServerAddressOptionName = "_UT_NOTIFICATIONS_SAMPLE_SERVER_ADDRESS";
        private bool m_notificationsEnabled;
        private string m_clickToHide = "(Click to hide)";
        private ReceivedNotification m_clickedNotification = null;
        private List<ReceivedNotification> m_receivedNotifications = new List<ReceivedNotification>();
    }
}