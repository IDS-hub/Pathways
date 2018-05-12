using UnityEngine;
using System.Collections;
using System.Collections.Generic;
using UTNotifications;

public class MyNotificationSystem : MonoBehaviour {

	string HEADING = "Pathways Pain Relief";
	string SUB_HEADING_1 = "How bad do you want pain relief? Consistent effort on our program will give you results.";
	string SUB_HEADING_2 = "Retraining your brain to stop pain can take time but it is possible - come back and complete another session.";

	int id = 0;

	void Start () {
		UTNotifications.Manager.Instance.Initialize (false, 0, true);

		UTNotifications.Manager.Instance.CancelAllNotifications();
		UTNotifications.Manager.Instance.ScheduleNotification(System.DateTime.Now.AddHours(24.0 * 5f), HEADING, SUB_HEADING_1, id++, null, "pathways");
		UTNotifications.Manager.Instance.ScheduleNotification(System.DateTime.Now.AddHours(24.0 * 10f), HEADING, SUB_HEADING_2, id++, null, "pathways");
	}
}
